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

    public function exchange($from, $to, $amount)
    {
        $url = 'https://api.apilayer.com/currency_data/convert?from='.$from.'&to='.$to.'&amount='.$amount.'&date='.date('Y-m-d');
        try {
            $client = new Client();
            $res = $client->request('GET', $url, [
                'headers' => [
                    'apikey' => 'rNh3nUyRaPsnhiX2x8ZKMR1Ij5CmNL8Q'
                ]
            ]);
            return json_decode($res->getBody(), true);
        } catch (\Exception $e) {
            return [
                "error" => "Error",
                "code"=> 0,
                "message"=> $e->getMessage()
            ];
        }
    }

    public function exchangeApi(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $result = $this->exchange($input['from'], $input['to'], $input['amount']);

        return response()->json($result);
    }

    public function exchangeForUser(Request $request)
    {
        $user = Auth::user();
        $userinfo = User::where('id', $user->id)->first();
        $bal = json_decode($userinfo->balance, true);

        $input = $request->all();

        $validator = Validator::make($input, [
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required'
        ]);

        if(!isset($bal[$input['from']]) || $bal[$input['from']] < $input['amount'])
        {
            return response()->json([
                "error" => "Exchange Error",
                "code"=> 0,
                "message"=> "Not enough money for ".$input['from']
            ]);
        }

        $result = $this->exchange($input['from'], $input['to'], $input['amount']);

        if(!isset($bal[$input['to']])) $bal[$input['to']] = 0;
        $bal[$input['to']] += $result['result'];
        $bal[$input['from']] -= $input['amount'];

        $userinfo->update(['balance' => json_encode($bal)]);

        return response()->json([]);
    }
}
