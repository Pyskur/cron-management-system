<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Configure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Permissions\Permission;

class ConfigureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_CONFIGURE_INDEX)) {
            $configures = Configure::all();
            return response()->json($configures);
        } else {
            return response->json([
                'error' => 'Error',
                'message' => 'Not Authorized',
            ]);
        }
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
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_CONFIGURE_STORE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required|string',
                'enabled' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation Error',
                    'code' => 0,
                    'message' => $validator->errors(),
                ]);
            }

            if (Configure::firstWhere('name', $input['name'])) {
                return response()->json([
                    'error' => 'Error',
                    'code' => 0,
                    'message' => 'Already Exist',
                ]);
            }

            try {
                $configure = Configure::create($input);
                return response()->json($configure);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                } else {
                    $message = 'Configure store error';
                }

                return response()->json([
                    'error' => 'Error',
                    'code' => 0,
                    'message' => $message
                ]);
            }
        } else {
            return response()->json([
                'error' => 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configure  $configure
     * @return \Illuminate\Http\Response
     */
    public function show(Configure $configure)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_CONFIGURE_SHOW)) {
            return response()->json($configure);
        } else {
            return response()->json([
                'error' => 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configure  $configure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Configure $configure)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_CONFIGURE_UPDATE)) {
            $input = $request->all();

            $validator = Validator::make($input, [
                'name' => 'required|string',
                'enabled' => 'boolean',
            ]);
    
            if($validator->fails()){
                return response()->json([
                    "error" => "Validation Error",
                    "code"=> 0,
                    "message"=> $validator->errors()
                ]);
            }

            try {
                $configure->update($input);
                return response()->json($configure);
            } catch (\Exception $e) {
                if (App::environment('local')) {
                    $message = $e->getMessage();
                } else {
                    $message = "Configure update error";
                }

                return response()->json([
                    "error" => "Error",
                    "code"=> 0,
                    "message"=> $message
                ]);
            }
        } else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configure  $configure
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configure $configure)
    {
        $user = Auth::user();
        if ($user->tokenCan(Permission::CAN_ALL) || $user->tokenCan(Permission::CAN_CONFIGURE_DESTROY)) {
            $configure->delete();
            return response()->json();
        } else {
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }
}
