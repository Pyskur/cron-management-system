<?php

namespace App\Http\Controllers\Api;

use App\Models\ResetCodePassword;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /**
     * @param  mixed $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return response()->json([
                'code' => false,
                'error' => 'Failed to reset',
                'message' => 'Reset code expired',
            ]);
        }

        $user = User::firstWhere('email', $passwordReset->email);

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        
        ResetCodePassword::where('code', $request->code)->delete();

        return response()->json(['userInfo' => $user]);
    }
}