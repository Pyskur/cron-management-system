<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keys = Key::all();
        return response()->json($keys);
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
        $input['user_id'] = Auth::user()->id;

        $validator = Validator::make($input, [
            'title' => 'required|string|max:50',
            'key' => 'required|string|size:32|alpha_num',
            'level' => 'required|numeric',
            'ignore_limits' => 'required|numeric',
            'is_private_key' => 'required|numeric',
            'ip_address' => 'required|string|max:50',
            'otp_key' => 'required|numeric',
            'video_enabled' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $key = Key::create($input);
            return response()->json($key);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Key store error";
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
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function show(Key $key)
    {
        return response()->json($key);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Key $key)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'nullable|string|max:50',
            'key' => 'nullable|string|size:32|alpha_num',
            'level' => 'nullable|numeric',
            'ignore_limits' => 'nullable|numeric',
            'is_private_key' => 'nullable|numeric',
            'ip_address' => 'nullable|string|max:50',
            'otp_key' => 'nullable|numeric',
            'video_enabled' => 'nullable|numeric',
            'user_id' => 'nullable|numeric',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $key->update($input);
            return response()->json($key);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Key update error";
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
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function destroy(Key $key)
    {
        $key->delete();
        return response()->json();
    }
}
