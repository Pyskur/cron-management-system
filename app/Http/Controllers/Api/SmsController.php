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

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $smses = Sms::all();
        return response()->json($smses);
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
            'number_to_send' => 'required|string|max:24',
            'date_expires' => 'required|numeric',
            'status' => 'required|numeric',
            'cost' => 'required|string|max:4',
            'charge' => 'required|string|max:4',
            'date_added' => 'required|date',
            'log' => 'required|numeric',
            'key_id' => 'required|numeric',
            'code_variable' => 'required|string|max:445',
            'user_id' => 'required|nullable|numeric'
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function show(Sms $sms)
    {
        return response()->json($sms);
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'number_to_send' => 'string|max:24',
            'date_expires' => 'numeric',
            'status' => 'numeric',
            'cost' => 'string|max:4',
            'charge' => 'string|max:4',
            'date_added' => 'date',
            'log' => 'numeric',
            'key_id' => 'numeric',
            'code_variable' => 'string|max:445',
            'user_id' => 'nullable|numeric'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sms  $sms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sms $sms)
    {
        $sms->delete();
        return response()->json();
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
