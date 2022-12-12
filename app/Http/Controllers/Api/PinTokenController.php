<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Permissions\Permission;
use App\Models\User;
use App\Models\PinToken;

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

class PinTokenController extends Controller
{
    public function setPasscode($data)
    {
        $user_email = $data['user_email'];
        $phone = User::firstWhere('email', $user_email)->phone;
        $passcode = mt_rand(100000, 999999);
        $expired_at = now()->addMinutes(5);

        $newdata = [
            'mobile_number' => $user_email,
            'passcode' => $passcode,
            'expired_at' => $expired_at,
        ];

        PinToken::create($newdata);

        if ($phone) {
            $message = 'The Passcode is ' . $passcode . ' and it expires at' . $expired_at;

            $sns_client = new SnsClient([
                'region' => $_ENV['AWS_DEFAULT_REGION'],
                'version' => '2010-03-31',
            ]);

            try {
                $result = $sns_client->publish([
                    'Message' => $message,
                    'PhoneNumber' => $phone,
                ]);
            } catch (AwsException $e) {
                return response()->json([
                    'Error' => 'Error: ' . $e->getAwsErrorMessage()
                ]);
            }
        }

        return response()->json($passcode, 200);
    }
}
