<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Limit;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;

class LimitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_LIMIT_INDEX)) {
            $limits = Limit::all();
            return response()->json($limits);
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_LIMIT_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'uri' => 'required|string|max:255',
                'count' => 'required|numeric',
                'hour_started' => 'required|numeric',
                'api_key' => 'required|string|max:40',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $limit = Limit::create($input);
                return response()->json($limit);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Limit store error";
                }
                return response()->json([
                    "error" => "Error",
                    "code"=> 0,
                    "message"=> $message
                ]);
            }
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Limit  $limit
     * @return \Illuminate\Http\Response
     */
    public function show(Limit $limit)
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_LIMIT_SHOW)) {
            return response()->json($limit);
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Limit  $limit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Limit $limit)
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_LIMIT_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'uri' => 'string|max:255',
                'count' => 'numeric',
                'hour_started' => 'numeric',
                'api_key' => 'string|max:40',
            ]);

            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $limit->update($input);
                return response()->json($limit);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Limit update error";
                }
                return response()->json([
                    "error" => "Error",
                    "code"=> 0,
                    "message"=> $message
                ]);
            }
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Limit  $limit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Limit $limit)
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_LIMIT_DELETE)) {
            $limit->delete();
            return response()->json();
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }
}
