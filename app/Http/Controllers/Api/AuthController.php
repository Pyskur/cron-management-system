<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register a User.
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => false,
                'message' => 'Invalid Inputs',
                'error' => $validator->errors()
            ], 401);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $token = $user->createToken('veri_token')->plainTextToken;

        return response()->json([
            'apiToken' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => false,
                'message' => 'Invalid Inputs',
                'error' => $validator->errors()
            ], 422);
        }

        $userdata = array('email' => $request->email, 'password' => $request->password);

        if (Auth::attempt($userdata, $request->remember)) {
            $user = Auth::user();
            $token = $user->createToken('veri_token')->plainTextToken;
            return response()->json([
                'api_token' => $token
            ], 200);
        } else {
            return response()->json([
                'code' => false,
                'message' => 'Invalid Credentials',
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        if (! $request->user()) {
            return response()->json([
                'code' => false,
                'message' => 'User is not logged on.',
            ]);
        }

        // Auth::logout();

        return response()->json([]);
    }
}