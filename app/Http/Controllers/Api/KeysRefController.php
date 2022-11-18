<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\KeysRef;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;
use App\Models\User;

class KeysRefController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSREF_INDEX)) {
            $keysRef = KeysRef::all();
            return response()->json($keysRef);
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
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
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSREF_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'ref1' => 'required|string|max:50',
                'ref2' => 'required|string|max:50',
                'ref3' => 'required|string|max:50'
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $keysRef = KeysRef::create($input);
                return response()->json($keysRef);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "KeysRef store error";
                }
                return response()->json([
                    "error" => "Error",
                    "code"=> 0,
                    "message"=> $message
                ]);
            }
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KeysRef  $keysRef
     * @return \Illuminate\Http\Response
     */
    public function show(KeysRef $keysRef)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSREF_SHOW)) {
            return response()->json($keysRef);
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KeysRef  $keysRef
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeysRef $keysRef)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSREF_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'ref1' => 'nullable|string|max:50',
                'ref2' => 'nullable|string|max:50',
                'ref3' => 'nullable|string|max:50'
            ]);

            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $keysRef->update($input);
                return response()->json($keysRef);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "KeysRef update error";
                }
                return response()->json([
                    "error" => "Error",
                    "code"=> 0,
                    "message"=> $message
                ]);
            }
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KeysRef  $keysRef
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeysRef $keysRef)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_KEYSREF_DESTROY)) {
            $keysRef->delete();
            return response()->json();
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }
}
