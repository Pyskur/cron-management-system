<?php

namespace App\Http\Controllers\Api;

use App\Models\ResetCodePassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CodeCheckRequest;
use App\Permissions\Permission;
use Illuminate\Support\Facades\Auth;

class CodeCheckController extends Controller
{
    /**
     * @param  mixed $request
     * @return void
     */
    public function __invoke(CodeCheckRequest $request)
    {
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        if ($passwordReset->isExpire()) {
            return response()->json();
        }

        return response()->json(['code' => $passwordReset->code]);
    }
}