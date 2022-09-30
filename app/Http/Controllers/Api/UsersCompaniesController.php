<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\UsersCompanies;
use Illuminate\Http\Request;
use Validator;

class UsersCompaniesController extends Controller
{
    /**
     * Display a listing of the Companies for a specific user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCompanies(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required|integer',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $companies = UsersCompanies::where('user_id', $input['user_id'])->pluck('company_id');
        return response()->json($companies);
    }

    /**
     * Display a listing of the Users for a specific company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'company_id' => 'required|integer',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $users = UsersCompanies::where('company_id', $input['company_id'])->pluck('user_id');
        return response()->json($users);
    }

    /**
     * Add a user to a company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addUsertoCompany(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'company_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $user_company = UsersCompanies::create($input);
            return response()->json($user_company);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "UsersCompanies store error";
            }
            return response()->json([
                "error" => "Error",
                "code"=> 0,
                "message"=> $message
            ]);
        }
    }

    public function addGrouptoCompany(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'company_id' => 'required|integer',
            'group_id' => 'required|integer',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $user_company = UsersCompanies::create($input);
            return response()->json($user_company);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "UsersCompanies store error";
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
    public function deleteUserfromCompany(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'company_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        UsersCompanies::where('company_id', $input['company_id'])->where('user_id', $input['user_id'])->delete();

        return response()->json();
    }
}
