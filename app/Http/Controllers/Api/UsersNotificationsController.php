<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\UsersNotifications;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;
use Illuminate\Support\Facades\Auth;

class UsersNotificationsController extends Controller
{
    /**
     * Display a listing of the Notifications for a specific user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNotifications(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required|uuid',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $Notifications = UsersNotifications::where('user_id', $input['user_id'])->pluck('notification_id');
        return response()->json($Notifications);
    }

    /**
     * Display a listing of the Users for a specific notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'notification_id' => 'required|uuid',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $users = UsersNotifications::where('notification_id', $input['notification_id'])->pluck('user_id');
        return response()->json($users);
    }

    /**
     * Add a user to a notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUsertoNotification(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'notification_id' => 'required|uuid',
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
            $user_notification = UsersNotifications::create($input);
            return response()->json($user_notification);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "UsersNotifications store error";
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteUserfromNotification(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'notification_id' => 'required|uuid',
            'user_id' => 'required|uuid',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        UsersNotifications::where('notification_id', $input['notification_id'])->where('user_id', $input['user_id'])->delete();

        return response()->json();
    }
}
