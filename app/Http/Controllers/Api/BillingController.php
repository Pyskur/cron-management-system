<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\billingdetails;
use App\Models\Video;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;
use Illuminate\Support\Facades\Auth;

use Aws\Athena\AthenaClient;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BILLING_INDEX)) {
            $billings = Billing::all();
            return response()->json($billings);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BILLING_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'type' => 'string',
                'amount' => 'numeric|between:0,99.99'
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $billing = Billing::create($input);
                return response()->json($billing);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Billing store error";
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
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show(Billing $billing)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BILLING_SHOW)) {
            return response()->json($billing);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billing $billing)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BILLING_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'type' => 'string',
                'amount' => 'numeric|between:0,99.99'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "error" => "Validation error",
                    "code" => 0,
                    "message" => $validator->errors()
                ]);
            }

            try {
                $billing->update($input);
                return response()->json($billing);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Billing update error";
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
                'message' => 'Not Authorized.'
            ]);
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BILLING_DESTROY)) {
            $billing->delete();
            return response()->json();
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    public function bandwidth()
    {
        $options = [
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION', 'eu-north-1'),
            'credentials' => [
               'key'    => env('AWS_ACCESS_KEY_ID', ""),
               'secret' => env('AWS_SECRET_ACCESS_KEY', "")
            ]
        ];
        $athenaClient = new \Aws\Athena\AthenaClient($options);
        
        $databaseName = 'videodb';
        $catalog = 'AwsDataCatalog';
        $sql = 'SELECT * FROM "videodb"."videodb" where request_uri like \'%GET%\' and objectsize > 0 and bytessent > 0 and parse_datetime(requestdatetime,\'dd/MMM/yyyy:HH:mm:ss Z\') BETWEEN parse_datetime(\''.date('Y-m-d').'\',\'yyyy-MM-dd\') AND parse_datetime(\''.date('Y-m-d', strtotime(' +1 day')).'\',\'yyyy-MM-dd\')';
        $outputS3Location = 's3://veri-vod-logs6819bb44-kg1h6qo3dy7x/destination-bucket-logs/';
        
         $startQueryResponse = $athenaClient->startQueryExecution([
            'QueryExecutionContext' => [
                'Catalog' => $catalog,
                'Database' => $databaseName
            ],
            'QueryString' => $sql,
            'ResultConfiguration'   => [
                'OutputLocation' => $outputS3Location
            ]
         ]);
        
        $queryExecutionId = $startQueryResponse->get('QueryExecutionId');
         // var_dump($queryExecutionId);
        
         $waitForSucceeded = function () use ($athenaClient, $queryExecutionId, &$waitForSucceeded) {
            $getQueryExecutionResponse = $athenaClient->getQueryExecution([
                'QueryExecutionId' => $queryExecutionId
            ]);
            $status = $getQueryExecutionResponse->get('QueryExecution')['Status']['State'];
            // print("[waitForSucceeded] State=$status\n");
            return $status === 'SUCCEEDED' || $waitForSucceeded();
         };
         $waitForSucceeded();
        
         $getQueryResultsResponse = $athenaClient->getQueryResults([
            'QueryExecutionId' => $queryExecutionId
         ]);
         
         $queryResult = $getQueryResultsResponse->get('ResultSet')["Rows"];
         $columnResult = $queryResult[0]["Data"];
         $columns = array();
         $data = array();
    
         // get columns
         for($i = 0 ; $i < count($columnResult) ; $i ++)
            $columns[$i] = $columnResult[$i]["VarCharValue"];
        
         // get data
         $bytes_per_video = array();
         for($i = 1 ; $i < count($queryResult) ; $i ++)
         {
            $currentRow = $queryResult[$i]["Data"];
            $data[$i - 1] = array();
            for($j = 0 ; $j < count($currentRow) ; $j ++)
            {
                $data[$i - 1][$columns[$j]] = $currentRow[$j]["VarCharValue"];
            }
            
            // parse video ID
            if(substr($data[$i - 1]['key'], 0, 4) == "test")
            {
                $data[$i - 1]["videoID"] = "test";
                continue ;
            }

            $data[$i - 1]["videoID"] = substr(explode("/", $data[$i - 1]['key'])[3], 0, 36);
            $bytes_per_video[$data[$i - 1]["videoID"]] = array();
            $bytes_per_video[$data[$i - 1]["videoID"]]["amount"] = 0;
            $bytes_per_video[$data[$i - 1]["videoID"]]["viewed"] = 0;
         }
    
        for($i = 0 ; $i < count($data) ; $i ++)
        {
            if($data[$i]["videoID"] == "test") continue ;
            $bytes_per_video[$data[$i]["videoID"]]["amount"] += $data[$i]["bytessent"];
            $bytes_per_video[$data[$i]["videoID"]]["viewed"] ++;
        }

        foreach($bytes_per_video as $key => $item)
        {
            $info = Video::where('uuid', $key)->get()->first();
            if($info)
            {
                $bytes_per_video[$key]["user_id"] = $info->user_id;

                // $user = new UserController;
                // $user->updateBalance($info->user_id, 'Bandwidth', $bytes_per_video[$key]["amount"] / 1024 / 1024 / 1024, 1);

                $billtype = Billing::firstWhere('type', 'Bandwidth');
                $billdetail = billingdetails::where('type', $billtype->id)->where('user_id', $info->user_id)->get()->first();
                $used_bytes = $bytes_per_video[$key]["amount"] + ($billdetail ? $billdetail->amount : 0);
                if($used_bytes / 1024 / 1024 / 1024 * $billtype->amount > 0.01)
                {
                    $bal = floor($used_bytes / 1024 / 1024 / 1024 * $billtype->amount * 100) / 100;
                    $used_bytes = floor($used_bytes - $bal * 1024 * 1024 * 1024 / $billtype->amount);

                    $user = User::firstWhere('id', $info->user_id);
                    User::where('id', $info->user_id)->update(['balance' => $user->balance - $bal]);
                }
                if($billdetail)
                {
                    billingdetails::where('type', $billtype->id)->where('user_id', $info->user_id)->update(['amount' => $used_bytes]);
                }
                else
                {
                    billingdetails::create([
                        "type" => $billtype->id,
                        "amount" => $used_bytes,
                        "user_id" => $info->user_id
                    ]);
                }

                $updates = ['views' => $info->views + $bytes_per_video[$key]['viewed'],
                            'bytes' => $info->bytes + $bytes_per_video[$key]['amount'],
                            'cost' => ($info->bytes + $bytes_per_video[$key]['amount']) / 1024 / 1024 / 1024 * $billtype->amount];
                Video::where('uuid', $key)->update($updates);
            }
        }

        return response()->json($bytes_per_video);
    }
}
