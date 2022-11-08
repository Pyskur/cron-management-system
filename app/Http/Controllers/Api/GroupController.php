<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\UsersGroups;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::all();
        return response()->json($groups);
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
            'name' => 'required|string|max:20',
            'description' => 'required|string|max:100',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $group = Group::create($input);
            return response()->json($group);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Group store error";
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
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return response()->json($group);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'nullable|string|max:50'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $group->update($input);
            return response()->json($group);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Group update error";
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
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return response()->json();
    }

    public function getUsers(Group $group){
        return response()->json($group->users);
    }

    public function initGroups(Request $request){
        if(Auth::user()->isAdmin()){
            Group::create(['name'=>'developers', 'description' =>'developers']);
            Group::create(['name'=>'administrators', 'description' =>'administrators']);
            Group::create(['name'=>'finance_staff', 'description' =>'finance staff']);
            Group::create(['name'=>'support_agents ', 'description' =>'support agents ']);
            Group::create(['name'=>'customers', 'description' =>'customers']);
            Group::create(['name'=>'3rd_party_developers', 'description' =>'3rd party developers']);
            return response()->json(["result"=>"success"]);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
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
