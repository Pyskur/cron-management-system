<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
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
        $blacklistip = BlacklistIps::firstWhere('ip_address', $ip);
        if ($blacklistip && $blacklistip->enabled) {
            return true;
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
