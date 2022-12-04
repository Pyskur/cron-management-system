<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Permissions\Permission;

class PinTokenController extends Controller
{
    public function setPasscode($data)
    {
        $user_email = $data['user_email'];
        $user_id = User::firstWhere('email', $user_email)->id;
        $passcode = mt_rand(100000, 999999);
        $expired_at = now()->addMinutes(5);

        $newdata = [
            'mobile_number' => $user_email,
            'passcode' => $passcode,
            'expired_at' => $expired_at,
        ];

        PinToken::create($newdata);

        $sms = new SmsController;
        $sms->sendUserPasscodeMessage_core(['user_id' => $user_id, 'passcode' => $passcode, 'expired_at' => $expired_at]);

        return response()->json($passcode, 200);
    }
}
