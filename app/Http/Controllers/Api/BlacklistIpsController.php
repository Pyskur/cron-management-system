<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\BlacklistIps;
use Illuminate\Http\Request;
use Validator;

class BlacklistIpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BlacklistIps  $blacklistIps
     * @return \Illuminate\Http\Response
     */
    public function show(BlacklistIps $blacklistIps)
    {
        //
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
     * @param  \App\Models\BlacklistIps  $blacklistIps
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlacklistIps $blacklistIps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BlacklistIps  $blacklistIps
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlacklistIps $blacklistIps)
    {
        //
    }
}
