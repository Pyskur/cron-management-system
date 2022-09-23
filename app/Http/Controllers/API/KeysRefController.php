<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\KeysRef;
use Illuminate\Http\Request;
use Validator;

class KeysRefController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keysRef = KeysRef::all();
        return response()->json($keysRef);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KeysRef  $keysRef
     * @return \Illuminate\Http\Response
     */
    public function show(KeysRef $keysRef)
    {
        return response()->json($keysRef);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KeysRef  $keysRef
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeysRef $keysRef)
    {
        $keysRef->delete();
        return response()->json();
    }
}
