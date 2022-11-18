<?php

namespace App\Http\Controllers\Api;

use App\Models\ResetCodePassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CodeCheckRequest;
use App\Permissions\Permission;

class CodeCheckController extends Controller
{
    /**
     * @param  mixed $request
     * @return void
     */
    public function __invoke(CodeCheckRequest $request)
    {
        if($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_CODECHECK_INVOKE)) {
            $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

            if ($passwordReset->isExpire()) {
                return response()->json();
            }

            return response()->json(['code' => $passwordReset->code]);
        }

        return response()->json([
            'error'=> 'Error',
            'message' => 'Not Authorized.'
        ]);
    }
}