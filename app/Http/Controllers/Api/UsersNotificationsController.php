<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\UsersNotifications;
use Illuminate\Http\Request;
use Validator;

class UsersNotificationsController extends Controller
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
     * @param  \App\Models\UsersNotifications  $usersNotifications
     * @return \Illuminate\Http\Response
     */
    public function show(UsersNotifications $usersNotifications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsersNotifications  $usersNotifications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UsersNotifications $usersNotifications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UsersNotifications  $usersNotifications
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsersNotifications $usersNotifications)
    {
        //
    }
}
