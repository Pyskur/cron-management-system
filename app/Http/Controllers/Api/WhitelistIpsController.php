<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\WhitelistIps;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class WhitelistIpsController extends Controller
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
     * @param  \App\Models\WhitelistIps  $whitelistIps
     * @return \Illuminate\Http\Response
     */
    public function show(WhitelistIps $whitelistIps)
    {
        //
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
     * @param  \App\Models\WhitelistIps  $whitelistIps
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WhitelistIps $whitelistIps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WhitelistIps  $whitelistIps
     * @return \Illuminate\Http\Response
     */
    public function destroy(WhitelistIps $whitelistIps)
    {
        //
    }
}
