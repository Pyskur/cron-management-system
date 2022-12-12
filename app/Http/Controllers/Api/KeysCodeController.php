<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\KeysCode;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class KeysCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSCODE_INDEX)) {
            $keysCodes = KeysCode::all();
            return response()->json($keysCodes);
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
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSCODE_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'type' => 'required|string|max:50',
                'length' => 'required|numeric',
                'otp_exp_time' => 'required|numeric'
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $keysCode = KeysCode::create($input);
                return response()->json($keysCode);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "KeysCode store error";
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
     * @param  \App\Models\KeysCode  $keysCode
     * @return \Illuminate\Http\Response
     */
    public function show(KeysCode $keysCode)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSCODE_SHOW)) {
            return response()->json($keysCode);
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
     * @param  \App\Models\KeysCode  $keysCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeysCode $keysCode)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSCODE_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'type' => 'nullable|string|max:50',
                'length' => 'nullable|numeric',
                'otp_exp_time' => 'nullable|numeric'
            ]);

            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $keysCode->update($input);
                return response()->json($keysCode);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "KeysCode update error";
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
     * @param  \App\Models\KeysCode  $keysCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeysCode $keysCode)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSCODE_DESTROY)) {
            $keysCode->delete();
            return response()->json();
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }
}
