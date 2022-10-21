<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Cron;
use Illuminate\Http\Request;
use Validator;

class CronController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crons = Cron::all();
        return response()->json($crons);
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
            'name' => 'required|string|max:50',
            'action' => 'required|string|max:50',
            'expression' => 'required|string|max:50',
            'is_running' => 'required|numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'timezone' => 'required|string|max:50',
            'location' => 'required|string|max:50',
            'user_id' => 'nullable|uuid'
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
            $cron = Cron::create($input);
            return response()->json($cron);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Cron store error";
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
     * @param  \App\Models\Cron  $cron
     * @return \Illuminate\Http\Response
     */
    public function show(Cron $cron)
    {
        //
        return response()->json($cron);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cron  $cron
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cron $cron)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'nullable|string|max:50',
            'action' => 'nullable|string|max:50',
            'expression' => 'nullable|string|max:50',
            'is_running' => 'nullable|numeric',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'timezone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:50',
            'user_id' => 'nullable|uuid'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $cron->update($input);
            return response()->json($cron);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Cron update error";
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
     * @param  \App\Models\Cron  $cron
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cron $cron)
    {
        $cron->delete();
        return response()->json();
    }
}
