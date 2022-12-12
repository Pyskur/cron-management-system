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
use Illuminate\Support\Facades\Auth;
use App\Permissions\Permission;
class VideoController extends Controller
{
    const VIDEO_STATUS_CREATED = 0;
    const VIDEO_STATUS_UPLOADED = 1;
    const VIDEO_STATUS_AVAILABLE = 2;
    const VIDEO_STATUS_EXPIRED = 3;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_VIDEO_INDEX)) {
            $videos = Video::all();
            return response()->json($videos);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_VIDEO_SHOW)) {
            if($user->isAdmin() || $user->id == $video->user_id)
            {
                return response()->json($video);
            }
            else{
                return response()->json([
                    'error'=> 'Error',
                    'message' => 'User is not owner of this video'
                ]);
            }
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
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
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_VIDEO_UPDATE)) {
            if($user->isAdmin() || $user->id == $video->user_id)
            {
                $input = $request->all();
                $validator = Validator::make($input, [
                    'title' => 'string',
                    'path' => 'string',
                    'status' => 'numeric',
                    'thumbnail' => 'string|max:45',
                    'drm_enabled' => 'numeric',
                    'publish_date' => 'date_format:Y-m-d H:i:s',
                    'unpublish_date' => 'date_format:Y-m-d H:i:s',
                    'black_list' => 'string|regex:/^(\[[a-z0-9, \-\"]*\])$/',
                    'white_list' => 'string|regex:/^(\[[a-z0-9, \-\"]*\])$/'
                ]);

                if($validator->fails()){
                    return response()->json([
                        "error" => "Validation Error",
                        "code"=> 0,
                        "message"=> $validator->errors()
                    ]);
                }

                // If Geo Block is changed , changes amend on AWS
                //// get GeoGroupID
                $newGeoGroupID = 0;
                if($request->white_list == null && $request->black_list == null){
                    //// nothing to update Geo Block
                }
                else if($request->black_list != null){
                    //// process black list
                    $countryIDList = json_decode($request->black_list);
                    if(count($countryIDList) > 0){
                        if($this->_isCountryListValid($countryIDList)){
                            $newGeoGroupID = $this->_getGeoGroupIDFromCountries($countryIDList, true);
                        }
                    }
                    else{
                        //global geo_group
                        $globalGeoGroup = GeoGroup::where('is_global', true)->first();
                        if($globalGeoGroup)
                            $newGeoGroupID = $globalGeoGroup->id;
                    }
                }
                else if($request->white_list != null){
                    //// process white list
                    $countryIDList = json_decode($request->white_list);
                    if($this->_isCountryListValid($countryIDList)){
                        $newGeoGroupID = $this->_getGeoGroupIDFromCountries($countryIDList, false);
                    }
                }

                if($newGeoGroupID > 0 && $video->geo_group_id != $newGeoGroupID){
                    

                    //// Geo Block setting is changed, changes have to be amended on AWS
                    $newGeoGroup = GeoGroup::find($newGeoGroupID);
                    $newVideoSubDirectory = $newGeoGroup->awsCloudfrontDistribution->dist_id;
                    
                    //// move s3 src file
                    $videoSrcUrlTokens = explode("/", $video->src_url);
                    $orgVideoSubDirectory = $videoSrcUrlTokens[5];
                    $orgPath = implode("/", array_slice($videoSrcUrlTokens, 3));
                    $newPath = str_replace($orgVideoSubDirectory, $newVideoSubDirectory, $orgPath);
                    if(Storage::disk('s3')->exists($orgPath)) {
                        $result = Storage::disk('s3')->move($orgPath, $newPath);
                    }
                    $newS3Url = str_replace($orgVideoSubDirectory, $newVideoSubDirectory, $video->src_url);

                    //// delete s3 dest folder
                    $s3DestUrl = $video->out_folder;
                    if(Storage::disk('s3-dest')->exists($s3DestUrl)) {
                        $result = Storage::disk('s3-dest')->deleteDirectory($s3DestUrl);
                    }

                    //// add model's field to update
                    $input["geo_group_id"] = $newGeoGroupID;
                    $input["src_url"] = $newS3Url;

                    
                }

                //// remove fields which are not model's fields
                unset($input["black_list"]);
                unset($input["white_list"]);

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
            else{
                return response()->json([
                    'error'=> 'Error',
                    'message' => 'User is not owner of this video'
                ]);
            }
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
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
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_VIDEO_DESTROY)) {
            if($user->isAdmin() || $user->id == $video->user_id)
            {
                //delete src s3 bucket source file
                $srcUrl = $video->src_url;
                $srcUrlTokens = explode("/", $srcUrl);
                $s3Url = implode("/", array_slice($srcUrlTokens, 3));
                $results3 = false;
                if(Storage::disk('s3')->exists($s3Url)) {
                    $results3 = Storage::disk('s3')->delete($s3Url);
                }

                //delete dest s3 bucket output folder
                $s3Url = $video->out_folder;
                $results3dest = false;
                if(Storage::disk('s3-dest')->exists($s3Url)) {
                    $results3dest = Storage::disk('s3-dest')->deleteDirectory($s3Url);
                }

                //delete table
                $video->delete();
                return response()->json(
                    [
                        "result" =>  true,
                        "results3" =>$results3,
                        "results3dest" =>$results3dest
                    ]
                );
            }
            else{
                return response()->json([
                    'error'=> 'Error',
                    'message' => 'User is not owner of this video'
                ]);
            }
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Upload video file and stores in AWS S3 bucket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadVideo(Request $request)
    {
        $userid = Auth::user()->id;
        $input = $request->all();
        $validator = Validator::make($input, [
            'title'=> 'required|string',
            'path'=> 'string',
            'file' => 'required|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi',
            'publish_date' => 'date_format:Y-m-d H:i:s',
            'unpublish_date' => 'date_format:Y-m-d H:i:s',
            'drm_enabled' =>'boolean',
            'black_list' => 'string|regex:/^(\[[a-z0-9, \-\"]*\])$/',
            'white_list' => 'string|regex:/^(\[[a-z0-9, \-\"]*\])$/'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ], 200,
            [
                'Access-Control-Allow-Origin' =>'*',
                'Access-Control-Allow-Methods' => '*'
            ]);
        }

        //Get Info of File
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
            if(count($countryIDList) > 0){
                if($this->_isCountryListValid($countryIDList)){
                    $geoGroupID = $this->_getGeoGroupIDFromCountries($countryIDList, true);
                }
            }
            else{
                //global geo_group
                $globalGeoGroup = GeoGroup::where('is_global', true)->first();
                if($globalGeoGroup)
                    $geoGroupID = $globalGeoGroup->id;
            }
        }
        else if($request->white_list != null){
            //process white list
            $countryIDList = json_decode($request->white_list);
            if($this->_isCountryListValid($countryIDList)){
                $geoGroupID = $this->_getGeoGroupIDFromCountries($countryIDList, false);
            }
        }

        if( $geoGroupID == 0){
            //no GeoGroup table
            return response()->json([
                "error" => "Error",
                "code" =>  0,
                "message" => "Cannot upload video. Geo Retriction is no valid."
            ], 200,
            [
                'Access-Control-Allow-Origin' =>'*',
                'Access-Control-Allow-Methods' => '*'
            ]);
        }

        //insert video table
        $path = $request->path != null ? $request->path : "/";
        $drm_enabled = $request->drm_enabled == null ? 0 : ($request->drm_enabled == 1 || $request->drm_enabled == "1" || $request->drm_enabled == true ? 1 : 0);
        $newVideo = [
            'title' => $request->title,
            'path' => $path,
            'filename' => $fileName,
            'status' => self::VIDEO_STATUS_CREATED,
            'file_size' => $fileSize,
            'user_id' => $userid,
            'uuid'=> '',
            'geo_group_id' => $geoGroupID,
            'publish_date'=> $request->publish_date,
            'unpublish_date'=> $request->unpublish_date,
            'drm_enabled' => $drm_enabled
        ];
        $video = Video::create($newVideo);

        $videoSubDirectory = $video->geoGroup->awsCloudfrontDistribution->dist_id;
        //Upload to S3 bucket
        $suffix = $drm_enabled == 1 ? "_drm" : "";
        $filePath = env('AWS_S3_BUCKET_FOLDER', 'assets01')."/videos/". $videoSubDirectory."/" . $video->id. $suffix. ".".$fileExt;
        $result = Storage::disk('s3')->put($filePath, file_get_contents($request->file));
        $path = Storage::disk('s3')->url($filePath);
        
        if($result){
            //Success uploading
           $video->update([
                'status' => self::VIDEO_STATUS_UPLOADED, // Encoding
                'src_url' => $path
           ]);

        }
        return response()->json([
            "result" =>  $result,
            "src_path" =>  $path,
            "video_id" => $video->id
        ], 200,
        [
            'Access-Control-Allow-Origin' =>'*',
            'Access-Control-Allow-Methods' => '*'
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
        // Only for Test
        Test::create([
            'data' => json_encode($request->json()->all())
        ]);
        
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
            $outFolder = $tokens[3]."/".$tokens[4];
            $filename = explode(".", $tokens[6])[0];
            $uuid = str_replace("_drm", "", $filename);
            $video = Video::find($uuid);
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

            //replace distribution url of public cdn url
            $cdnDomainName = $video->geoGroup->awsCloudfrontDistribution->alt_domain_name;
            $globalGeoGroup = GeoGroup::where('is_global', true)->first();
            if($globalGeoGroup == null){
                return response()->json([
                    "type" => "Error",
                    "result" => "Unknown uuid from Notification"
                ]);
                
            }
            $originPrefix = $globalGeoGroup->awsCloudfrontDistribution->domain_name."/".$video->geoGroup->awsCloudfrontDistribution->dist_id;
            $outputURL = str_replace($originPrefix, $cdnDomainName, $outputURL);


            $thumbnailUrl = $message->Outputs->THUMB_NAILS[0];
            $thumbnailUrl = str_replace($originPrefix, $cdnDomainName, $thumbnailUrl);
            $thumbnailTokens = explode(".", $thumbnailUrl);
            $thumbnailCountText = array_slice($thumbnailTokens, -2, 1)[0];
            $thumbnailCount = intval($thumbnailCountText) + 1;
            $thumbnailTokens[count($thumbnailTokens) - 2] = str_pad(intval($thumbnailCount / 2), 7, '0', STR_PAD_LEFT);
            $thumbnailUrl = implode(".", $thumbnailTokens);

            if($video->drm_enabled){
                $outputURL_DASHISO = $message->Outputs->DASH_ISO_GROUP[0];
                $outputURL_APPLE = $message->Outputs->HLS_GROUP[0];
                $outputUrl_DASHISO_tokens = explode("/", $outputURL_DASHISO);
                $dest_url_DASHISO = implode("/", array_slice($outputUrl_DASHISO_tokens, 3));
                $content = Storage::disk('s3-dest')->get($dest_url_DASHISO);
                preg_match("/default_KID=\"([0-9a-z-]*)\"/", $content, $matched);
                $kid = $matched[1];

                $originPrefix = $globalGeoGroup->awsCloudfrontDistribution->domain_name."/".$video->geoGroup->awsCloudfrontDistribution->dist_id;
                $outputURL = str_replace($originPrefix, $cdnDomainName, $outputURL_DASHISO);

                $outputURLAPPLE = str_replace($originPrefix, $cdnDomainName, $outputURL_APPLE);
                $outputURLDASH = str_replace($originPrefix, $cdnDomainName, $outputURL_DASHISO);
             }
            else{
                $kid = null;
                $outputURLAPPLE = null;
                $outputURLDASH = null;
            }
            //update video table with out result information
            $video->update([
                'status' => self::VIDEO_STATUS_AVAILABLE,
                'out_url' => $outputURL,
                'out_url_apple' => $outputURLAPPLE,
                'out_url_dash' => $outputURLDASH,
                'out_folder' => $outFolder,
                'out_folder_size' => $sizeData["statusCode"] == 200 ? $sizeData["data"]["size"] : 0,
                'thumbnail' => $thumbnailUrl,
                'thumbnail_count' => $thumbnailCount,
                'drm_keyid' => $kid
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
            'alt_domain_name' => $newDomainName,
            'origin' => $distributionResult["Origins"],
        ]; 
        $newAwsCloudfrontDistribution = AwsCloudfrontDistribution::create($data);
                            
        ////create new geogroup with aws_cloudfront_distributions.id
        $newGeoGroup = GeoGroup::create([
            'is_blacklist' => $isBlacklist,
            'is_global' => false,
            'uuid'=> (string) Str::uuid(),
            'aws_cloudfront_distribution_id' =>$newAwsCloudfrontDistribution->id
        ]);

        ////create new country_geo_group_maps with geogroup.id and countryIDList
        foreach($countryIDList as $country){
            $addData = [
                'country_id' => $country,
                'geo_group_id' => $newGeoGroup->id
            ];   
            CountryGeoGroupMap::create($addData);
        }
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

        $globalDistConfig = $this->_getDistributionConfig($cloudFrontClient, env('AWS_CLOUDFRONT_DISTRIBUTION_GLOBAL_ID'));
        if (array_key_exists('Error', $globalDistConfig)) {
            return [
                "Error" => $globalDistConfig['Error']
            ];          
        }

        $globalS3OriginConfig = $globalDistConfig["DistributionConfig"]["Origins"]["Items"][0]["S3OriginConfig"];

        $currentConfig['DistributionConfig']["Origins"]["Items"][0]["OriginPath"] = "/".$distributionID;
        $currentConfig['DistributionConfig']["Origins"]["Items"][0]["S3OriginConfig"] = $globalS3OriginConfig;

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

    public function getStatus(Video $video)
    {
        if(Auth::user()->isAdmin() || Auth::user()->id == $video->user_id)
        {
            $statusText = "";
            if($video->status == self::VIDEO_STATUS_CREATED)
                $statusText = "CREATED";
            else if($video->status == self::VIDEO_STATUS_UPLOADED)
                $statusText = "UPLOADED";
            else if($video->status == self::VIDEO_STATUS_AVAILABLE)
                $statusText = "AVAILABLE";
            


            if($video->isExpired()){
                $statusText = "EXPIRED";
            }
        
            return response()->json([
                'id' => $video->id,
                'statusCode' => $video->status,
                'statusText' => $statusText
            ]);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'User is not owner of this video'
            ]);
        }
    }

    public function getPlaybackUrl(Request $request, Video $video)
    {
        if($video->status == self::VIDEO_STATUS_AVAILABLE){
            if($video->isPublished() && !$video->isExpired()){
                if($video->drm_enabled){
                    $outUrl = $video->out_url;
                    if($request->browser == "chrome"){
                        $outUrl = $video->out_url_dash;
                    }
                    else if($request->browser == "safari"){
                        $outUrl = $video->out_url_apple;
                    }
                    $validFrom = date(DATE_ATOM, strtotime("yesterday"));
                    $validTo = date(DATE_ATOM, strtotime("tomorrow"));
                    $message = [
                        "type" => "entitlement_message",
                        "version" => 2,
                        "license" => [
                            "start_datetime" => $validFrom,
                            "expiration_datetime" => $validTo,
                            "allow_persistence" => true
                        ],
        
                        "content_keys_source" => [
                            "inline" => [
                                [
                                    "id" => $video->drm_keyid,
                                    "usage_policy" => "Policy A"		
                                ] 
                            ]
                        ],
        
                        // NOTE: This is for global
                        // The keys list will be filled separately by the next code block.
                        // "content_keys_source" => [
                        //     "license_request" => [
                        //       "seed_id" => env('AXINOM_KEY_SEED_ID'),
                        //       "usage_policy" => "Policy A"
                        //     ]
                        // ],
                        
                        // License configuration should be as permissive as possible for the scope of this guide.
                        // For this reason, some PlayReady-specific restrictions are relaxed below.
                        // There is no need to relax the default Widevine or FairPlay specific restrictions.
                        "content_key_usage_policies" => [
                            [
                                "name" => "Policy A"
                            ]
                        ]
                    ];
        
                    $envelope = [
                        "version" => 1,
                        "com_key_id" => env('AXINOM_COM_KEY_ID'),
                        "message" => $message,
                        "begin_date" => $validFrom,
                        "expiration_date" => $validTo
                    ];
                    $key = base64_decode(env('AXINOM_COM_KEY'));
                    $licenseToken = \Firebase\JWT\JWT::encode($envelope, $key, 'HS256');
                    return response()->json(
                        [
                            "url" => $outUrl,
                            "licenseToken" => $licenseToken
                        ], 200,
                        [
                            'Access-Control-Allow-Origin' =>'*'
                        ]
                    );
                }
                else{
                    return response()->json(
                        [
                            "url" => $video->out_url
                        ]
                    );
                }
            }
            else{
                return response()->json(
                    [
                        "message"=>"expired"
                    ]
                );
            }
        }
        else{
            return response()->json(
                [
                    "message"=>"video is not ready."
                ]
            );
        }
        
    }
    public function getThumbnailsList(Video $video)
    {
        if(Auth::user()->isAdmin() || Auth::user()->id == $video->user_id)
        {
            $thumbnailUrl = $video->thumbnail;
            $thumbnailCount = $video->thumbnail_count;
            $thumbnailTokens = explode(".", $thumbnailUrl);
            if(count($thumbnailTokens) >= 3 && $thumbnailCount > 0){
                $result = [];
                for ($index = 0; $index < $thumbnailCount; $index++){
                    $thumbnailTokens[count($thumbnailTokens) - 2] = str_pad($index, 7, '0', STR_PAD_LEFT);
                    $result[] = implode(".", $thumbnailTokens);
                }
                return response()->json(
                    $result
                );
            }
            else{
                return response()->json([
                    'error'=> 'Error',
                    'message' => 'Thumbnail Url is not valid'
                ]);
            }
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'User is not owner of this video'
            ]);
        }
        

    }
    function _isCountryListValid($countryIDList){
        if(count($countryIDList) == 0)
            return false;
        foreach($countryIDList as $countryID){
            $country = Country::find($countryID);
            if(!$country){
                return false;
            }
        }
        return true;
    }

    public function getVideosByPath(Request $request){
        $path = $request->path;
        $recursive = $request->recursive;
        if($recursive == 1 || $recursive == "1")
            $videos = Video::where('user_id', Auth::user()->id)->where('path', 'like', $path.'%')->get();
        else
            $videos = Video::where('user_id', Auth::user()->id)->where('path', $path)->get();
        return response()->json($videos);
    }

    public function getVideosByPathAdmin(Request $request){
        if(Auth::user()->isAdmin()){
            $path = $request->path;
            $user_id = $request->user_id;
            $recursive = $request->recursive;
            if($recursive == 1 || $recursive == "1")
                $videos = Video::where('user_id', $user_id)->where('path', 'like', $path.'%')->get();
            else
                $videos = Video::where('user_id', $user_id)->where('path', $path)->get();
            return response()->json($videos);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }

    }
    
    public function test(Request $request)
    {

        //Test
        $dest_url = "EY6CESNM58DSQ/a9fc8797-e6d2-4337-a4e2-e7ccd8bf895d/DASHISO1/ac5c6969-38d8-4f6e-bbfe-410f3acc1347_drm.mpd";
        $content = Storage::disk('s3-dest')->get($dest_url);
        preg_match("/default_KID=\"([0-9a-z-]*)\"/", $content, $matched);
        $kid = $matched[1];
        return response()->json([
            'message' => $kid
        ]);
    }

}
