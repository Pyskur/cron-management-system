<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\KeysCode;
use Illuminate\Http\Request;
use Validator;

class KeysCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keysCodes = KeysCode::all();
        return response()->json($keysCodes);
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
            'type' => 'required|string|max:50',
            'length' => 'required|numeric',
            'otp_exp_time' => 'required|numeric'
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $keysCode = KeysCode::create($input);
            return response()->json($keysCode);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "KeysCode store error";
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
     * @param  \App\Models\KeysCode  $keysCode
     * @return \Illuminate\Http\Response
     */
    public function show(KeysCode $keysCode)
    {
        return response()->json($keysCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KeysCode  $keysCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeysCode $keysCode)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'type' => 'nullable|string|max:50',
            'length' => 'nullable|numeric',
            'otp_exp_time' => 'nullable|numeric'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            // $keysCode->update($input);
            return response()->json($keysCode);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "KeysCode update error";
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
     * @param  \App\Models\KeysCode  $keysCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeysCode $keysCode)
    {
        $keysCode->delete();
        return response()->json();
    }
}
