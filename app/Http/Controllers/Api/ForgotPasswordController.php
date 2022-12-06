<?php

namespace App\Http\Controllers\Api;

use App\Models\ResetCodePassword;
use App\Http\Controllers\Mail\SendCodeResetPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use App\Permissions\Permission;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    /**
     * @param  mixed $request
     * @return void
     */
    public function __invoke(ForgotPasswordRequest $request)
    {
        ResetCodePassword::where('email', $request->email)->delete();

        $codeData = ResetCodePassword::create($request->data());

        Mail::to($request->email)->queue(new SendCodeResetPassword($codeData->code));
        // Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        $user = new UserController;
        $user->updateBalance($request->email, 'Otp');

        $user = User::firstWhere('email', $request->email);

        return response()->json($user);
    }
}