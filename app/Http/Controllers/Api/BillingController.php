<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;
use Validator;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $billings = Billing::all();
        return response()->json($billings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'type' => 'string',
            'amount' => 'numeric|between:0,99.99'
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $billing = Billing::create($input);
            return response()->json($billing);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Billing store error";
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
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show(Billing $billing)
    {
        return response()->json($billing);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billing $billing)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'type' => 'string',
            'amount' => 'numeric|between:0,99.99'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "error" => "Validation error",
                "code" => 0,
                "message" => $validator->errors()
            ]);
        }

        try {
            $billing->update($input);
            return response()->json($billing);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Billing update error";
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
     * @param  \App\Models\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        $billing->delete();
        return response()->json();
    }
}
