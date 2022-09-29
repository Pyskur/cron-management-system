<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Sms;
use Illuminate\Http\Request;
use Validator;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $smses = Sms::all();
        return response()->json($smses);
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
            'number_to_send' => 'required|string|max:24',
            'date_expires' => 'required|numeric',
            'status' => 'required|numeric',
            'cost' => 'required|string|max:4',
            'charge' => 'required|string|max:4',
            'date_added' => 'required|date',
            'log' => 'required|numeric',
            'key_id' => 'required|numeric',
            'code_variable' => 'required|string|max:445',
            'user_id' => 'required|nullable|numeric'
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $input['uniqueid'] = 'sdfasdfasdf'; // must be random string

        try {
            $sms = Sms::create($input);
            return response()->json($sms);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Sms store error";
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
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function show(Sms $sms)
    {
        return response()->json($sms);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sms $sms)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'number_to_send' => 'string|max:24',
            'date_expires' => 'numeric',
            'status' => 'numeric',
            'cost' => 'string|max:4',
            'charge' => 'string|max:4',
            'date_added' => 'date',
            'log' => 'numeric',
            'key_id' => 'numeric',
            'code_variable' => 'string|max:445',
            'user_id' => 'nullable|numeric'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $sms->update($input);
            return response()->json($sms);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Sms update error";
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
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sms $sms)
    {
        $sms->delete();
        return response()->json();
    }
}
