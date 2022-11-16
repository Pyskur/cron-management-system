<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Billing;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use function Ramsey\Uuid\v1;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_USER_INDEX)) {
            $users = User::all();
            return response()->json($users);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
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
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_USER_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required|string|min:3|max:50',
                'email' => 'required|string|email|max:100|unique:users',
                'first_name' => 'required|string|min:3|max:50',
                'last_name' => 'required|string|min:3|max:50',
                'country_id' => 'required|numeric',
                'password' => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'language' => 'required|string|min:3|max:50',
            ]);
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
            $customer_info = $stripe->customers->create([
                'description' => 'Veri User',
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            $newdata = [
                'name'=> $request->name,
                'email'=> $request->email,
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'country_id'=> $request->country_id,
                'password'=> bcrypt($request->password),
                'phone'=> $request->phone,
                'language'=> $request->language,
                'stripe_cust_id' => $customer_info->id,
            ];

            $user = User::create($newdata);

            $sms = new SmsController;
            $sms->sendUserVerificationMessage_core(['user_id' => $user->id]);

            return response()->json($user);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $auth_user = Auth::user();
        if ($auth_user->tokenCan(Permission::CAN_ALL) || $auth_user->tokenCan(Permission::CAN_USER_SHOW)) {
            if($auth_user->isAdmin())
            {
                return response()->json($user);
            }
            else if($auth_user->id == $user->id){
                return response()->json(
                    [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'active' => $user->active,
                        'phone' => $user->phone,
                        'rate' => $user->rate,
                        'balance' => $user->balance,
                        'is_verify' => $user->is_verify,
                        'language' => $user->language,
                        'country_id' => $user->country_id,
                    ]
                );
            }
            else{
                return response()->json([
                    'error'=> 'Error',
                    'message' => 'User is not owner.'
                ]);
            }
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $auth_user = Auth::user();
        if ($auth_user->tokenCan(Permission::CAN_ALL) || $auth_user->tokenCan(Permission::CAN_USER_UPDATE)) {
            if($auth_user->isAdmin() || $auth_user->id == $user->id)
            {
                $input = $request->all();
                $validator = Validator::make($input, [
                    'name' => 'nullable|string|min:3|max:50',
                    'email' => 'nullable|string|email|max:100|unique:users',
                    'first_name' => 'nullable|string|min:3|max:50',
                    'last_name' => 'nullable|string|min:3|max:50',
                    'country_id' => 'nullable|numeric',
                    'password' => 'nullable|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
                    'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                    'language' => 'nullable|string|min:3|max:50',
                    'balance' => 'nullable|numeric',
                ]);
                if($validator->fails()){
                    return response()->json([
                        "error" => "Validation Error",
                        "code"=> 0,
                        "message"=> $validator->errors()
                    ]);
                }

                $newdata = [];
                if($request->name){
                    $newdata['name'] = $request->name;
                }
                if($request->email){
                    $newdata['email'] = $request->email;
                }
                if($request->first_name){
                    $newdata['first_name'] = $request->first_name;
                }
                if($request->last_name){
                    $newdata['last_name'] = $request->last_name;
                }
                if($request->company_id){
                    $newdata['company_id'] = $request->company_id;
                }
                if($request->password){
                    $newdata['password'] = bcrypt($request->password);
                }
                if($request->phone){
                    $newdata['phone'] = $request->phone;
                }
                if($request->country_id){
                    $newdata['country_id'] = $request->country_id;
                }
                if($request->language){
                    $newdata['language'] = $request->language;
                }
                if($request->balance){
                    $newdata['balance'] = $request->balance;
                }
                $result = $user->update($newdata);

                if($request->email)
                {
                    $sms = new SmsController;
                    $sms->sendUserVerificationMessage_core(['user_id' => $user->id]);
                }

                return response()->json($user);
            }
            else{
                return response()->json([
                    'error'=> 'Error',
                    'message' => 'User is not owner.'
                ]);
            }
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }       
    }

    /**
     * Update the balance in storage
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateBalance($email, $type, $size = 1)
    {
        $user = User::firstWhere('email', $email);
        $balance = Billing::firstWhere('type', $type)->amount;

        $newdata = [];
        $newdata['balance'] = $user->balance - $balance;

        if ($newdata['balance'] < 0) {
            return response()->json([
                "error" => "Balance error",
                "code" => 0,
                "message" => "Low Balance"
            ]);
        }

        $user->update($newdata);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {        
        $auth_user = Auth::user();
        if ($auth_user->isAdmin()) {
            $user->delete();
            return response()->json([
                'message'=> 'Success'
            ]);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    public function getCompanies(User $user)
    {
        return response()->json($user->companies);
    }
    public function getGroups(User $user)
    {
        return response()->json($user->groups);
    }
    
}
