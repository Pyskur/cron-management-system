<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();
        return response()->json($users);
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'company_id' => 'required|numeric',
            'country_id' => 'required|numeric',
            'password' => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'language' => 'required|string|min:3|max:50',
        ]);
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $newdata = [
            'name'=> $request->name,
            'email'=> $request->email,
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'company_id'=> $request->company_id,
            'country_id'=> $request->country_id,
            'password'=> bcrypt($request->password),
            'phone'=> $request->phone,
            'language'=> $request->language,
        ];

        $user = User::create($newdata);

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'nullable|string|min:3|max:50',
            'email' => 'nullable|string|email|max:100|unique:users',
            'first_name' => 'nullable|string|min:3|max:50',
            'last_name' => 'nullable|string|min:3|max:50',
            'company_id' => 'nullable|numeric',
            'country_id' => 'nullable|numeric',
            'password' => 'nullable|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'language' => 'nullable|string|min:3|max:50',
        ]);
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $newdata = [];
        if($request->name){
            $newdata['name'] = $request->name;
        }
        if($request->email){
            $newdata['email'] = $request->email;
        }
        if($request->first_name){
            $newdata['first_name'] = $request->first_name;
        }
        if($request->last_name){
            $newdata['last_name'] = $request->last_name;
        }
        if($request->company_id){
            $newdata['company_id'] = $request->company_id;
        }
        if($request->password){
            $newdata['password'] = bcrypt($request->password);
        }
        if($request->phone){
            $newdata['phone'] = $request->phone;
        }
        if($request->country_id){
            $newdata['country_id'] = $request->country_id;
        }
        if($request->language){
            $newdata['language'] = $request->language;
        }
        $result = $user->update($newdata);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json();
    }
}
