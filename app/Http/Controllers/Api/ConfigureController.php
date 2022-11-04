<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Configure;
use Illuminate\Http\Request;

class ConfigureController extends Controller
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
     * @param  \App\Models\Configure  $configure
     * @return \Illuminate\Http\Response
     */
    public function show(Configure $configure)
    {
        //
    }

    /**
     * Display the specified configure is enabled
     */
    public function isEnabled($data)
    {
        $name = $data['name'];
        $configure = Configure::firstWhere('name', $name);
        if ($configure && $configure->enabled) {
            return true;
        }
        
        return false;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configure  $Configure
     * @return \Illuminate\Http\Response
     */
    public function edit(Configure $configure)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configure  $configure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configure $configure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configure  $configure
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configure $configure)
    {
        //
    }
}
