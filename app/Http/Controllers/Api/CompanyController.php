<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\UsersGroups;
use App\Models\UsersCompanies;
use Illuminate\Http\Request;
use Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        return response()->json($companies);
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
            'name' => 'required|string|max:50',
            'sso_token' => 'required|string|max:255',
            'billing_detail' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'whitelist_ip' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
            'color1' => 'required|string|max:10',
            'color2' => 'required|string|max:10',
            'color3' => 'required|string|max:10',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $company = Company::create($input);
            return response()->json($company);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Company store error";
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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'string|max:50',
            'sso_token' => 'string|max:255',
            'billing_detail' => 'string|max:255',
            'address' => 'string|max:255',
            'domain' => 'string|max:255',
            'whitelist_ip' => 'string|max:255',
            'logo' => 'string|max:255',
            'color1' => 'string|max:10',
            'color2' => 'string|max:10',
            'color3' => 'string|max:10',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $company->update($input);
            return response()->json($company);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Company update error";
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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json();
    }

    public function getUsers(Company $company){
        return response()->json($company->users);
    }

    public function addUsertoCompany(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'company_id' => 'required|uuid',
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
            'company_id' => 'required|uuid',
            'group_id' => 'required|uuid',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $companies = array();
            $users = UsersGroups::where('group_id', $input['group_id'])->pluck('user_id');
            foreach($users as $user)
            {
                $companies[] = UsersCompanies::create(['user_id' => $user, 'company_id' => $input['company_id']]);
            }
            return response()->json($companies);
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
            'company_id' => 'required|uuid',
            'user_id' => 'required|uuid',
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
