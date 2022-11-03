<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Str;
use Dirape\Token\Token;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->isAdmin()){
            $keys = Key::all();
            return response()->json($keys);
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
        $input = $request->all();


        $validator = Validator::make($input, [
            'title' => 'required|string|max:50',
            'level' => 'required|numeric',
            'ignore_limits' => 'required|numeric',
            'is_private_key' => 'required|numeric',
            'ip_address' => 'string|regex:/^(\[.*\])$/',
            'permissions' => 'string|regex:/^(\[.*\])$/',
        ]);

        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $input['user_id'] = Auth::user()->id;
            $input['key'] = (new Token())->Unique('keys', 'key', 32);
            $input['ip_address'] = json_decode($input['ip_address']);
            if($input['permissions'] == null)
                $input['permissions'] = [];
            else
                $input['permissions'] = json_decode($input['permissions']);
            //TODO key_smses
            //
            //$input['keys_sms_id'] = '';

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
        if(Auth::user()->isAdmin() || Auth::user()->id == $key->user_id)
        {
            return response()->json($key);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'User is not owner of this Key'
            ]);
        }
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
        if(Auth::user()->isAdmin() || Auth::user()->id == $key->user_id)
        {
            $input = $request->all();

            $validator = Validator::make($input, [
                'title' => 'nullable|string|max:50',
                'level' => 'nullable|numeric',
                'ignore_limits' => 'nullable|numeric',
                'is_private_key' => 'nullable|numeric',
                'ip_address' => 'string|regex:/^(\[.*\])$/',
                'permissions' => 'string|regex:/^(\[.*\])$/',
            ]);
            //TODO key_smses
            //

            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            //unset some not updating allowed field
            if(array_key_exists('key', $input)){
                unset($input["key"]);
            }
            if(array_key_exists('user_id', $input)){
                unset($input["user_id"]);
            }
            if(array_key_exists('keys_sms_id', $input)){
                unset($input["keys_sms_id"]);
            }

            try {
                $input['ip_address'] = json_decode($input['ip_address']);
                $input['permissions'] = json_decode($input['permissions']);
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
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'User is not owner of this Key'
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
        if(Auth::user()->isAdmin() || Auth::user()->id == $key->user_id)
        {
            $key->delete();
            return response()->json();
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'User is not owner of this Key'
            ]);
        }
    }
}
