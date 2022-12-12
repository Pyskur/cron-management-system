<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_COUNTRY_INDEX)) {
            $countries = Country::all();
            return response()->json($countries);
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
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_COUNTRY_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required|string|max:50',
                'code' => 'required|string|size:2',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }
    
            try {
                $country = Country::create($input);
                return response()->json($country);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Country store error";
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
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_COUNTRY_SHOW)) {
            return response()->json($country);
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
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_COUNTRY_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'string|max:50',
                'code' => 'string|size:2',
            ]);
    
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }
    
            try {
                $country->update($input);
                return response()->json($country);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Country update error";
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
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_COUNTRY_DESTROY)) {
            $country->delete();
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
