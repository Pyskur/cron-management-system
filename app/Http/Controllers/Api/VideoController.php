<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Test;
use App\Models\GeoGroup;
use App\Models\AwsCloudfrontDistribution;
use App\Models\CountryGeoGroupMap;
use App\Models\Country;
class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::all();
        return response()->json($videos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
 
        $validator = Validator::make($input, [
            'title' => 'required|string',
            'filename' => 'required|string',
            'status' => 'required|numeric',
            'file_size' => 'required|string|max:45',
            'geo_restrict' => 'required|numeric',
            'thumbnail' => 'required|string|max:45',
            'parent_name' => 'required|string|max:45',
            'url' => 'required|string',
            'drm_enabled' => 'required|numeric',
            'user_id' => 'required|numeric'
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $video = Video::create($input);
            return response()->json($video);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Video store error";
            }
            return response()->json([
                "error" => "Error",
                "code"=> 0,
                "message"=> $message
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return response()->json($video);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'nullable|string',
            'filename' => 'nullable|string',
            'status' => 'nullable|numeric',
            'file_size' => 'nullable|string|max:45',
            'geo_restrict' => 'nullable|numeric',
            'thumbnail' => 'nullable|string|max:45',
            'parent_name' => 'nullable|string|max:45',
            'url' => 'nullable|string',
            'drm_enabled' => 'nullable|numeric',
            'user_id' => 'nullable|numeric'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $video->update($input);
            return response()->json($video);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Video update error";
            }
            return response()->json([
                "error" => "Error",
                "code"=> 0,
                "message"=> $message
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return response()->json();
    }

    /**
     * Upload video file and stores in AWS S3 bucket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadVideo(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'title'=> 'required|string',
            'file' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        //Get Info of File
        $uuid = (string) Str::uuid();
        $fileName = $request->file->getClientOriginalName();
        $fileExt = $request->file->getClientOriginalExtension();
        $fileSize = $request->file->getSize();

        //get GeoGroupID
        $geoGroupID = 0;
        if($request->white_list == null && $request->black_list == null){
            //global geo_group
            $globalGeoGroup = GeoGroup::where('is_global', true)->first();
            if($globalGeoGroup)
                $geoGroupID = $globalGeoGroup->id;
        }
        else if($request->black_list != null){
            //process black list
            $countryIDList = json_decode($request->black_list);
            $geoGroupID = $this->_getGeoGroupIDFromCountries($countryIDList, true);
        }
        else if($request->white_list != null){
            //process white list
            $countryIDList = json_decode($request->white_list);
            $geoGroupID = $this->_getGeoGroupIDFromCountries($countryIDList, false);
        }


        //insert video table
        $newVideo = [
            'title' => $request->title,
            'filename' => $fileName,
            'status' => 1, //uploading
            'file_size' => $fileSize,
            'user_id' => 1, //TODO,
            'uuid'=> $uuid,
            'geo_group_id' => $geoGroupID
        ];
        $video = Video::create($newVideo);

        $videoSubDirectory = $video->geoGroup->awsCloudfrontDistribution->dist_id;
        //Upload to S3 bucket
        $filePath = env('AWS_S3_BUCKET_FOLDER', 'assets01')."/videos/". $videoSubDirectory."/" . $uuid. ".".$fileExt;
        $result = Storage::disk('s3')->put($filePath, file_get_contents($request->file));
        $path = Storage::disk('s3')->url($filePath);
        
        if($result){
            //Success uploading
           $video->update([
                'status' => 2, // Encoding
                'src_url' => $path
           ]);

        }
        return response()->json([
            "result" =>  $result,
            "src_path" =>  $path,
            "video_id" => $video->id
        ]);
    }
    /**
     * Webhook to receive uploaded url from AWS SNS.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hookVideoUploaded(Request $request)
    {
        $data = $request->json()->all();
        //Only for Test
        // Test::create([
        //     'data' => json_encode($request->json()->all())
        // ]);
        if($data == null || !array_key_exists('Type', $data)){
            return response()->json([
                "type" => "Error",
                "result" => "Input Data Error"
            ]);
        }

        if($data["Type"] == "SubscriptionConfirmation"){
            //Confirm Subscription of AWS SNS once
            $subscribeURL = $data["SubscribeURL"];
            $response = Http::get($subscribeURL);
            return response()->json([
                "type" => "SubscriptionConfirmation",
                "result" => $response->body()
            ]);

        }
        else if($data["Type"] == "Notification"){
            //Notification of AWS SNS
            $message = json_decode($data["Message"]);
            $outputURL = $message->Outputs->HLS_GROUP[0];
            $tokens = explode("/", $outputURL);
            $outFolder = $tokens[3];
            $uuid = explode(".", $tokens[5])[0];
            $video = Video::where('uuid', $uuid)->first();
            if($video == null){
                return response()->json([
                    "type" => "Error",
                    "result" => "Unknown uuid from Notification"
                ]);
            }

            //get folder size of AWS S3
            $apiUrl = env('AWS_S3_API_GET_FOLDER_SIZE');
            $apiBucketName = env('AWS_S3_DESTINATION_BUCKET');
            $getFolderSizeUrl = "{$apiUrl}?bucketName={$apiBucketName}&folderPath={$outFolder}";
            $outFolderSizeResponse = Http::get($getFolderSizeUrl);
            $sizeData = $outFolderSizeResponse->json();
            $video->update([
                'status' => 3, // Available
                'out_url' => $outputURL,
                'out_folder' => $outFolder,
                'out_folder_size' => $sizeData["statusCode"] == 200 ? $sizeData["data"]["size"] : 0,
            ]);

            return response()->json([
                "type" => "Notification",
                "result" => $video,
                "out"=>$sizeData,
                "url"=>$getFolderSizeUrl
            ]);
        } 
        else{
            return response()->json([
                "type" => "Error",
                "result" => $data
            ]);
        }
    }

    function _getGeoGroupIDFromCountries($countryIDList, $isBlacklist){
        sort($countryIDList);
        foreach (GeoGroup::where('is_global', false)->where('is_blacklist', $isBlacklist)->cursor() as $geoGroup) {
            $geoGrouopCountries = [];
            foreach($geoGroup->countries as $country){
                $geoGrouopCountries[] = $country->id;
            }
            sort($geoGrouopCountries);
            if($countryIDList == $geoGrouopCountries){
                //Found existig GeoGroup, so return existing GeoGroup ID
                return $geoGroup->id;
            }
        }

        //Not found existing GeoGroup, so create new GeoGroup

        ////aws sdk to create aws_cloudfront_distributions
        $distributionResult = $this->_createCloudFrontDistribution($countryIDList, $isBlacklist);

        ////add custom doman to aws Route53
        $newDomainName = $this->_addCustomDomain($distributionResult["DomainName"]);
        ////add certificate, alternative domain name to cloudfront distribution
        $this->_updateCloudfrontDistributionWithAlias($distributionResult["ID"], $newDomainName);
        ////create new aws_cloudfront_distributions
        $data = [
            'dist_id' => $distributionResult["ID"],
            'description' => $distributionResult["Description"],
            'domain_name' => $distributionResult["DomainName"],
            'alt_domain_name' => $distributionResult["AltDomainName"],
            'origin' => $distributionResult["Origins"],
        ]; 
        $newAwsCloudfrontDistribution = AwsCloudfrontDistribution::create($data);
                            
        ////create new geogroup with aws_cloudfront_distributions.id
        $newGeoGroup = GeoGroup::create([
            'is_blacklist' => $isBlacklist,
            'is_global' => false,
            'aws_cloudfront_distribution_id' =>$newAwsCloudfrontDistribution->id
        ]);

        ////create new country_geo_group_maps with geogroup.id and countryIDList
        $addData = [];
        foreach($countryIDList as $country){
            $addData[] = [
                'country_id' => $country,
                'geo_group_id' => $newGeoGroup->id
            ];   
        }
        CountryGeoGroupMap::insert($addData);
        return $newGeoGroup->id;
    }

    function _createCloudFrontDistribution($countryIDList, $isBlacklist)
    {
        
        $s3BucketURL = env('AWS_S3_DESTINATION_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION', 'us-east-1').'.amazonaws.com';
        $originName = $s3BucketURL;
        $uuid = (string)Str::uuid();
        $callerReference = config('app.name').$uuid;
        $defaultCacheBehavior = [
            'AllowedMethods' => [
                'CachedMethods' => [
                    'Items' => ['HEAD', 'GET'],
                    'Quantity' => 2
                ],
                'Items' => ['HEAD', 'GET'],
                'Quantity' => 2
            ],
            'Compress' => false,
            'DefaultTTL' => 0,
            'FieldLevelEncryptionId' => '',
            'ForwardedValues' => [
                'Cookies' => [
                    'Forward' => 'none'
                ],
                'Headers' => [
                    'Quantity' => 0
                ],
                'QueryString' => false,
                'QueryStringCacheKeys' => [
                    'Quantity' => 0
                ]
            ],
            'LambdaFunctionAssociations' => ['Quantity' => 0],
            'MaxTTL' => 0,
            'MinTTL' => 0,
            'SmoothStreaming' => false,
            'TargetOriginId' => $originName,
            'TrustedSigners' => [
                'Enabled' => false,
                'Quantity' => 0
            ],
            'ViewerProtocolPolicy' => 'allow-all'
        ];
        $enabled = true;
        $origin = [
            'Items' => [
                [
                    'DomainName' => $s3BucketURL,
                    'Id' => $originName,
                    'OriginPath' => '',
                    'CustomHeaders' => ['Quantity' => 0],
                    'S3OriginConfig' => ['OriginAccessIdentity' => '']
                ]
            ],
            'Quantity' => 1
        ];


        //make description and geoRestriction Country List
        $geoRestrictionItems = [];
        $description = "";
        if ($isBlacklist)
            $description .= "Block: ";
        else
            $description .= "Allow: ";
        foreach($countryIDList as $countryID){
            $country = Country::find($countryID);
            if($country){
                $code = $country->code;
                $description .= $code . ' ';  
                $geoRestrictionItems[] = $code;
            }
        }

        $geoRestriction = [
            'GeoRestriction' => [
                'Items' => $geoRestrictionItems,
                'Quantity' => count($geoRestrictionItems),
                'RestrictionType' => $isBlacklist ? 'blacklist' : 'whitelist'
            ]
        ];

        $distribution = [
            'CallerReference' => $callerReference,
            'Comment' => $description,
            'DefaultCacheBehavior' => $defaultCacheBehavior,
            'Enabled' => $enabled,
            'Origins' => $origin,
            'Restrictions' => $geoRestriction
        ];

        $cloudFrontClient = \AWS::createClient('CloudFront');
        try {
            $result = $cloudFrontClient->createDistribution([
                'DistributionConfig' => $distribution
            ]);

            if (isset($result['Distribution']))
            {
                $resultData = [
                    "ID" => $result['Distribution']["Id"],
                    "Description" => $result['Distribution']["DistributionConfig"]["Comment"],
                    "DomainName" => $result['Distribution']["DomainName"],
                    "AltDomainName" => '',
                    "Origins" => $result['Distribution']["DistributionConfig"]["DefaultCacheBehavior"]["TargetOriginId"],

                ];
                return $resultData;
            }
            return [];
        } catch (AwsException $e) {
            return [];
        }
    }
    function _addCustomDomain($redirectDomain){
        $uuid = (string) Str::uuid();
        $uuid = str_replace("-", "", $uuid);
        $newDomainName = $uuid.env('AWS_ROUTE53_APP_CDN_NAME','.cdn.veri.app');
        $changeBatch = [
            'Comment' => 'Add new custom domain for cloudfront distribution geo restriction',
            'Changes' => [
                [
                    'Action' => 'CREATE',
                    'ResourceRecordSet' => [
                        'Name' => $newDomainName,
                        'Type' => 'CNAME',
                        'TTL' => 600,
                        'ResourceRecords' => [
                            [
                                'Value' => $redirectDomain,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $route53Client = \AWS::createClient('Route53');
        try {
            $result = $route53Client->changeResourceRecordSets([
                'HostedZoneId' => env('AWS_ROUTE53_APP_HOSTED_ZONE_ID'),
                'ChangeBatch' => $changeBatch
            ]);

            if (isset($result['ChangeInfo']))
            {
                return $newDomainName;
            }
            return "";
        } catch (AwsException $e) {
            return "";
        }
    }

    function _getDistributionConfig($cloudFrontClient, $distributionId)
    {
        try {
            $result = $cloudFrontClient->getDistribution([
                'Id' => $distributionId,
            ]);

            if (isset($result['Distribution']['DistributionConfig']))
            {
                return [
                    'DistributionConfig' => $result['Distribution']['DistributionConfig'],
                    'effectiveUri' => $result['@metadata']['effectiveUri']
                ];
            } else {
                return [
                    'Error' => 'Error: Cannot find distribution configuration details.',
                    'effectiveUri' => $result['@metadata']['effectiveUri']
                ];
            }

        } catch (AwsException $e) {
            return [
                'Error' => 'Error: ' . $e->getAwsErrorMessage()
            ];
        }
    }

    function _getDistributionETag($cloudFrontClient, $distributionId)
    {

        try {
            $result = $cloudFrontClient->getDistribution([
                'Id' => $distributionId,
            ]);
            
            if (isset($result['ETag']))
            {
                return [
                    'ETag' => $result['ETag'],
                    'effectiveUri' => $result['@metadata']['effectiveUri']
                ]; 
            } else {
                return [
                    'Error' => 'Error: Cannot find distribution ETag header value.',
                    'effectiveUri' => $result['@metadata']['effectiveUri']
                ];
            }

        } catch (AwsException $e) {
            return [
                'Error' => 'Error: ' . $e->getAwsErrorMessage()
            ];
        }
    }

    function _updateCloudfrontDistributionWithAlias($distributionID, $altDomainName){
        $cloudFrontClient = \AWS::createClient('CloudFront');

        $eTag = $this->_getDistributionETag($cloudFrontClient, $distributionID);

        if (array_key_exists('Error', $eTag)) {
            return [
                "Error" => $eTag['Error']
            ];
        }

        $currentConfig = $this->_getDistributionConfig($cloudFrontClient, $distributionID);

        if (array_key_exists('Error', $currentConfig)) {
            return [
                "Error" => $currentConfig['Error']
            ];          
        }

        $distributionConfig = [
            'Aliases' => [
                'Items' => [$altDomainName],
                'Quantity' => 1
            ],
            'CallerReference' => $currentConfig['DistributionConfig']["CallerReference"], 
            'Comment' => $currentConfig['DistributionConfig']["Comment"], 
            'DefaultCacheBehavior' => $currentConfig['DistributionConfig']["DefaultCacheBehavior"], 
            'DefaultRootObject' => $currentConfig['DistributionConfig']["DefaultRootObject"],
            'Enabled' => $currentConfig['DistributionConfig']["Enabled"], 
            'Origins' => $currentConfig['DistributionConfig']["Origins"], 
            'CustomErrorResponses' => $currentConfig['DistributionConfig']["CustomErrorResponses"],
            'HttpVersion' => $currentConfig['DistributionConfig']["HttpVersion"],
            'CacheBehaviors' => $currentConfig['DistributionConfig']["CacheBehaviors"],
            'Logging' => $currentConfig['DistributionConfig']["Logging"],
            'PriceClass' => $currentConfig['DistributionConfig']["PriceClass"],
            'Restrictions' => $currentConfig['DistributionConfig']["Restrictions"],
            'ViewerCertificate' => [
                'ACMCertificateArn' => env('AWS_SSL_CERTIFICATE_APP'),
                'Certificate' => env('AWS_SSL_CERTIFICATE_APP'),
                'CertificateSource' => 'acm',
                'CloudFrontDefaultCertificate' => false,
                'MinimumProtocolVersion' => 'TLSv1.2_2021',
                'SSLSupportMethod' => 'sni-only',
            ],
            'WebACLId' => $currentConfig['DistributionConfig']["WebACLId"]
        ];


        try {
            $result = $cloudFrontClient->updateDistribution([
                'Id' => $distributionID,
                'DistributionConfig' => $distributionConfig,
                'IfMatch' => $eTag['ETag']
            ]);

            if (isset($result['Distribution']))
            {
                $resultData = [
                    "ID" => $result['Distribution']["Id"]
                ];
                return $resultData;
            }
            return [];
        } catch (AwsException $e) {
            return [];
        }
    }
    public function test(Request $request)
    {

        //Test
        // var_dump($this->_addCustomDomain("dotvk0avcnmxl.cloudfront.net"));
        var_dump($this->_updateCloudfrontDistributionWithAlias("E35IFL7A49UVRY", 'd87cc77a47ba495b9c9a3ac223d8c8d6.cdn.veri.app'));
        // $cloudFrontClient = \AWS::createClient('CloudFront');
        // var_dump($this->_getDistributionConfig($cloudFrontClient, "EY6CESNM58DSQ"));
    }
}
