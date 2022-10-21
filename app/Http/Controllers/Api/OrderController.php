<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
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
            'user_name' => 'required|string',
            'email' => 'required|string',
            'plan_id' => 'required|string|max:100',
            'plan_name' => 'required|string',
            'payment_response' => 'required|string',
            'payment_status' => 'required|string|max:55',
            'created_date' => 'required|string',
            'amount' => 'required|string|max:55',
            'client_secret' => 'required|string',
            'fingerprint' => 'required|string',
            'charge_id' => 'required|string|max:100',
            'customer_id' => 'required|string|max:100',
            'currency' => 'required|string|max:50',
            'exp_month' => 'required|numeric',
            'exp_year' => 'required|numeric',
            'card_st_digit' => 'required|numeric',
            'user_id' => 'required|uuid'
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $order = Order::create($input);
            return response()->json($order);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Order store error";
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_name' => 'nullable|string',
            'email' => 'nullable|string',
            'plan_id' => 'nullable|string|max:100',
            'plan_name' => 'nullable|string',
            'payment_response' => 'nullable|string',
            'payment_status' => 'nullable|string|max:55',
            'created_date' => 'nullable|string',
            'amount' => 'nullable|string|max:55',
            'client_secret' => 'nullable|string',
            'fingerprint' => 'nullable|string',
            'charge_id' => 'nullable|string|max:100',
            'customer_id' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:50',
            'exp_month' => 'nullable|numeric',
            'exp_year' => 'nullable|numeric',
            'card_st_digit' => 'nullable|numeric',
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
            $order->update($input);
            return response()->json($order);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Order update error";
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json();
    }
}
