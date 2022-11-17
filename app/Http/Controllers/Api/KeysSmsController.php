<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\KeysSms;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;

class KeysSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSSMS_INDEX)) {
            $keysSms = KeysSms::all();
            return response()->json($keysSms);
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
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSSMS_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'from' => 'required|string|max:10',
                'text' => 'required|string',
                'status' => 'required|string|max:50',
                'friendly_name' => 'required|string'
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $keysSms = KeysSms::create($input);
                return response()->json($keysSms);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "keysSms store error";
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
     * @param  \App\Models\KeysSms  $keysSms
     * @return \Illuminate\Http\Response
     */
    public function show(KeysSms $keysSms)
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSSMS_SHOW)) {
            return response()->json($keysSms);
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
     * @param  \App\Models\KeysSms  $keysSms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeysSms $keysSms)
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSSMS_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'from' => 'nullable|string|max:10',
                'text' => 'nullable|string',
                'status' => 'nullable|string|max:50',
                'friendly_name' => 'nullable|string'
            ]);

            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $keysSms->update($input);
                return response()->json($keysSms);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "KeysSms update error";
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
     * @param  \App\Models\KeysSms  $keysSms
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeysSms $keysSms)
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSSMS_DELETE)) {
            $keysSms->delete();
            return response()->json();
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }
}
