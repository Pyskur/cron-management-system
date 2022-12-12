<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Models\Money;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Permissions\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class MoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $money = Money::all();
        return response()->json($money);
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
            'name' => 'required|string',
            'rate' => 'required',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $money = Money::create($input);
            return response()->json($money);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Money store error";
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
     * @param  \App\Models\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function show(Money $money)
    {
        return response()->json($money);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function edit(Money $money)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Money $money)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
            'rate' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $money->update($input);
            return response()->json($money);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Money update error";
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
     * @param  \App\Models\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function destroy(Money $money)
    {
        $money->delete();
        return response()->json();
    }

    public function exchange(Request $request)
    {
        $client = new Client();
        $res = $client->request('POST', 'https://api.apilayer.com/currency_data/convert', [
            'form_params' => [
                'from' => 'USD',
                'to' => 'EUR',
                'amount' => '5',
                'date' => '2022-01-01',
            ],
            'headers' => [
                'apikey' => 'rNh3nUyRaPsnhiX2x8ZKMR1Ij5CmNL8Q'
            ]
        ]);
        echo $res->getStatusCode();
        // 200
        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        echo $res->getBody();
    }
}
