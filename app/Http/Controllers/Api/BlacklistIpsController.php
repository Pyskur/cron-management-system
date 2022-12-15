<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\BlacklistIps;
use Illuminate\Http\Request;
use Validator;
use App\Permissions\Permission;

class BlacklistIpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BLACKLISTIPS_INDEX)) {
            $blacklistIps = BlacklistIps::all();
            return response()->json($blacklistIps);
        } else {
            return response()->json([
                'error' => 'Error',
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
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BLACKLISTIPS_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'ip_address' => 'required|string',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $blacklistIp = BlacklistIps::create($input);
                return response()->json($blacklistIp);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "BlacklistIp store error";
                }
                return response()->json([
                    "error" => "Error",
                    "code"=> 0,
                    "message"=> $message
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
     * Display the specified resource.
     *
     * @param  \App\Models\BlacklistIps  $blacklistIp
     * @return \Illuminate\Http\Response
     */
    public function show(BlacklistIps $blacklistIp)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BLACKLISTIPS_SHOW)) {
            return response()->json($blacklistIp);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Display the specified ip is blocked
     */
    public function isBlocked($data)
    {
        $ip = $data['login_ip'];
        $ranges = BlacklistIps::all();

        foreach ($ranges as $range) {
            if ($range->enabled) {
                list($subnet, $bits) = explode('/', $range->ip_address);
                if ($bits === null) {
                    $bits = 32;
                }
                $ip = ip2long($ip);
                $subnet = ip2long($subnet);
                $mask = -1 << (32 - intval($bits));
                $subnet &= $mask;

                if (($ip & $mask) == $subnet) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BlacklistIps  $blacklistIps
     * @return \Illuminate\Http\Response
     */
    public function edit(BlacklistIps $blacklistIps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BlacklistIps  $blacklistIp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlacklistIps $blacklistIp)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BLACKLISTIPS_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required|string',
            ]);
    
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }
    
            try {
                $blacklistIp->update($input);
                return response()->json($blacklistIp);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "BlacklistIp update error";
                }
                return response()->json([
                    "error" => "Error",
                    "code"=> 0,
                    "message"=> $message
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlacklistIps  $blacklistIp
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlacklistIps $blacklistIp)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_BLACKLISTIPS_DESTROY)) {
            $blacklistIp->delete();
            return response()->json();
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }
}
