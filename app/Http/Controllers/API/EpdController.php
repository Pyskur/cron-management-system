<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Epd;
use Illuminate\Http\Request;
use Validator;
class EpdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $epds = Epd::all();
        return response()->json($epds);
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
            'epd' => 'required|numeric',
            'epd_interval' => 'required|numeric',
            'timeout' => 'required|numeric',
            'epd_daily' => 'required|numeric',
            'service_type' => 'required|numeric',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $epd = Epd::create($input);
            return response()->json($epd);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Epd store error";
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
     * @param  \App\Models\Epd  $epd
     * @return \Illuminate\Http\Response
     */
    public function show(Epd $epd)
    {
        return response()->json($epd);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Epd  $epd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Epd $epd)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'epd' => 'nullable|numeric',
            'epd_interval' => 'nullable|numeric',
            'timeout' => 'nullable|numeric',
            'epd_daily' => 'nullable|numeric',
            'service_type' => 'nullable|numeric',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $epd->update($input);
            return response()->json($epd);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Epd update error";
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
     * @param  \App\Models\Epd  $epd
     * @return \Illuminate\Http\Response
     */
    public function destroy(Epd $epd)
    {
        $epd->delete();
        return response()->json();
    }
}
