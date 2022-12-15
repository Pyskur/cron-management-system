<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\WhitelistIps;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Permissions\Permission;

class WhitelistIpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_WHITELISTIPS_INDEX)) {
            $whitelistIps = WhitelistIps::all();
            return response()->json($whitelistIps);
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
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_WHITELISTIPS_STORE)) {
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
                $whitelistIp = WhitelistIps::create($input);
                return response()->json($blacklistIp);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "WhitelistIp store error";
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
     * @param  \App\Models\WhitelistIps  $whitelistIp
     * @return \Illuminate\Http\Response
     */
    public function show(WhitelistIps $whitelistIp)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_WHITELISTIPS_SHOW)) {
            return response()->json($whitelistIp);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Display the specified ip is allowed
     */
    public function isAllowed($data)
    {
        $ip = $data['login_ip'];
        $ranges = WhitelistIps::all();

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
     * @param  \App\Models\WhitelistIps  $whitelistIps
     * @return \Illuminate\Http\Response
     */
    public function edit(WhitelistIps $whitelistIps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WhitelistIps  $whitelistIp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WhitelistIps $whitelistIp)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_WHITELISTIPS_UPDATE)) {
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
                $whitelistIp->update($input);
                return response()->json($whitelistIp);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                }
                else{
                    $message = "WhitelistIp update error";
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
     * @param  \App\Models\WhitelistIps  $whitelistIp
     * @return \Illuminate\Http\Response
     */
    public function destroy(WhitelistIps $whitelistIp)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_WHITELISTIPS_DESTROY)) {
            $whitelistIp->delete();
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
