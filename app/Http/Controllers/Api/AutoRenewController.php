<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\AutoRenew;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;
class AutoRenewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_AUTORENEW_INDEX)) {
            $autoRenews = AutoRenew::all();
            return response()->json($autoRenews);
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
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_AUTORENEW_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'auto_renew_min_amt' => 'required|string',
                'auto_renew_amt' => 'required|string|max:50',
                'auto_renewal' => 'required|string|max:100',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $autoRenew = AutoRenew::create($input);
                return response()->json($autoRenew);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "AutoRenew store error";
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
     * @param  \App\Models\AutoRenew  $autoRenew
     * @return \Illuminate\Http\Response
     */
    public function show(AutoRenew $autoRenew)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_AUTORENEW_SHOW)) {
            return response()->json($autoRenew);
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
     * @param  \App\Models\AutoRenew  $autoRenew
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AutoRenew $autoRenew)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_AUTORENEW_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'auto_renew_min_amt' => 'nullable|string',
                'auto_renew_amt' => 'nullable|string|max:50',
                'auto_renewal' => 'nullable|string|max:100',
            ]);

            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $autoRenew->update($input);
                return response()->json($autoRenew);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Key update error";
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
     * @param  \App\Models\AutoRenew  $autoRenew
     * @return \Illuminate\Http\Response
     */
    public function destroy(AutoRenew $autoRenew)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_AUTORENEW_DESTROY)) {
            $autoRenew->delete();
            return response()->json();
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }
}
