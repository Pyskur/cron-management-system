<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_NOTIFICATION_INDEX)) {
            $notifications = Notification::all();
            return response()->json($notifications);
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
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_NOTIFICATION_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required|string',
                'status' => 'required|string',
                'user_id' => 'required|uuid',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $notification = Notification::create($input);
                return response()->json($notification);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Notification store error";
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
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_NOTIFICATION_SHOW)) {
            return response()->json($notification);
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
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_NOTIFICATION_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'nullable|string',
                'status' => 'nullable|string',
                'user_id' => 'nullable|uuid',
            ]);

            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $notification->update($input);
                return response()->json($notification);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Notification update error";
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
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_NOTIFICATION_DESTROY)) {
            $notification->delete();
            return response()->json();
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }
}
