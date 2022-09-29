<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\KeysSms;
use Illuminate\Http\Request;
use Validator;

class KeysSmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keysSms = KeysSms::all();
        return response()->json($keysSms);
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
            'from' => 'required|string|max:10',
            'text' => 'required|string',
            'status' => 'required|string|max:50',
            'friendly_name' => 'required|string'
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $keysSms = KeysSms::create($input);
            return response()->json($keysSms);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "keysSms store error";
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
     * @param  \App\Models\KeysSms  $keysSms
     * @return \Illuminate\Http\Response
     */
    public function show(KeysSms $keysSms)
    {
        return response()->json($keysSms);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KeysSms  $keysSms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeysSms $keysSms)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'from' => 'nullable|string|max:10',
            'text' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'friendly_name' => 'nullable|string'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $keysSms->update($input);
            return response()->json($keysSms);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "KeysSms update error";
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
     * @param  \App\Models\KeysSms  $keysSms
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeysSms $keysSms)
    {
        $keysSms->delete();
        return response()->json();
    }
}
