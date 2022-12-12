<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Sms;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Jobs\LeadSendmailJob;
use App\Http\Controllers\Jobs\VerificationSendMailJob;
use Illuminate\Support\Facades\Mail;
use App\Permissions\Permission;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_SMS_INDEX)) {
            $smses = Sms::all();
            return response()->json($smses);
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
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_SMS_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'number_to_send' => 'required|string|max:24',
                'date_expires' => 'required|numeric',
                'status' => 'required|numeric',
                'cost' => 'required|string|max:4',
                'charge' => 'required|string|max:4',
                'date_added' => 'required|date',
                'log' => 'required|numeric',
                'key_id' => 'required|uuid',
                'code_variable' => 'required|string|max:445',
                'user_id' => 'required|nullable|uuid'
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
                $sms = Sms::create($input);
                return response()->json($sms);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Sms store error";
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
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function show(Sms $sms)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_SMS_SHOW)) {
            return response()->json($sms);
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
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sms $sms)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_SMS_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'number_to_send' => 'string|max:24',
                'date_expires' => 'numeric',
                'status' => 'numeric',
                'cost' => 'string|max:4',
                'charge' => 'string|max:4',
                'date_added' => 'date',
                'log' => 'numeric',
                'key_id' => 'uuid',
                'code_variable' => 'string|max:445',
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
                $sms->update($input);
                return response()->json($sms);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "Sms update error";
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
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sms $sms)
    {
        $user = Auth::user();
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_SMS_DESTROY)) {
            $sms->delete();
            return response()->json();
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }

    public function generateUniqueCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        return $code;
    }

    public function sendUserPasscodeMessage_core($data)
    {
        try
        {
            $userid = $data['user_id'];
            $passcode = $data['passcode'];
            $expired = $data['expired_at'];
            $userinfo = User::where('id', $userid)->first();
            $this->dispatch(new VerificationSendMailJob(array('email' => $userinfo->email, 'name' => $userinfo->name, 'verification_code' => $ucode, 'verification_code_expiry' => $expired)));
            return 0;
        }
        catch (\Exception $e)
        {
            return $e;
        }
    }

    public function sendUserVerificationMessage(Request $request)
    {
        $data = $request->all();

        $e = $this->sendUserVerificationMessage_core($data);
        if($e === 0) {
            return response()->json(['status'=>'true']);
        }
        return response()->json(['status'=>'false','msg'=>$e->getMessage()]);
    }

    public function sendUserVerificationMessage_core($data)
    {
        try
        {
            $userid = $data['user_id'];
            $ucode = $this->generateUniqueCode();
            $expired = time() + 5 * 60;
            User::where('id', $userid)->update(['verification_code' => $ucode]);
            User::where('id', $userid)->update(['verification_code_expiry' => $expired]);
            $userinfo = User::where('id', $userid)->first();
            $this->dispatch(new VerificationSendMailJob(array('email' => $userinfo->email, 'name' => $userinfo->name, 'verification_code' => $ucode, 'verification_code_expiry' => $expired)));
            return 0;
        }
        catch (\Exception $e)
        {
            return $e;
        }
    }

    public function sendMessage(Request $request)
    {
        try
        {
            $data=$request->all();
            $this->dispatch(new LeadSendmailJob($data));
            return response()->json(['status'=>'true']);
        }
        catch (\Exception $e)
        {
            return response()->json(['status'=>'false','msg'=>$e->getMessage()]);
        }
    }
}
