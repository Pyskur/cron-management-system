<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\UsersGroups;
use Illuminate\Http\Request;
use Validator;

class UsersGroupsController extends Controller
{
    /**
     * Display a listing of the Groups for a specific user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getGroups(Request $request)
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

        $Groups = UsersGroups::where('user_id', $input['user_id'])->pluck('group_id');
        return response()->json($Groups);
    }

    /**
     * Display a listing of the Users for a specific group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'group_id' => 'required|uuid',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $users = UsersGroups::where('group_id', $input['group_id'])->pluck('user_id');
        return response()->json($users);
    }

    /**
     * Add a user to a group.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUsertoGroup(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'group_id' => 'required|uuid',
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
            $user_group = UsersGroups::create($input);
            return response()->json($user_group);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "UsersGroups store error";
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
    public function deleteUserfromGroup(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'group_id' => 'required|uuid',
            'user_id' => 'required|uuid',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        UsersGroups::where('group_id', $input['group_id'])->where('user_id', $input['user_id'])->delete();

        return response()->json();
    }
}
