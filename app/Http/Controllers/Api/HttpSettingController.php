<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\HttpSetting;
use Illuminate\Http\Request;
use Validator;

class HttpSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $httpSetting = HttpSetting::all();
        return response()->json($httpSetting);
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
            'authentication' => 'required|numeric',
            'authentication_username' => 'required|string|max:50',
            'authentication_password' => 'required|string|max:50',
            'method' => 'required|string|max:50',
            'message_body' => 'required|string|max:50',
            'headers' => 'required|string|max:50',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $httpSetting = HttpSetting::create($input);
            return response()->json($httpSetting);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "HttpSetting store error";
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
     * @param  \App\Models\HttpSetting  $httpSetting
     * @return \Illuminate\Http\Response
     */
    public function show(HttpSetting $httpSetting)
    {
        return response()->json($httpSetting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HttpSetting  $httpSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HttpSetting $httpSetting)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'authentication' => 'numeric',
            'authentication_username' => 'string|max:50',
            'authentication_password' => 'string|max:50',
            'method' => 'string|max:50',
            'message_body' => 'string|max:50',
            'headers' => 'string|max:50',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $httpSetting->update($input);
            return response()->json($httpSetting);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "HttpSetting update error";
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
     * @param  \App\Models\HttpSetting  $httpSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(HttpSetting $httpSetting)
    {
        $httpSetting->delete();
        return response()->json();
    }
}
