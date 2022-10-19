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
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'country_id' => 'required|numeric',
            'password' => 'required|string|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'language' => 'required|string|min:3|max:50',
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        $customer_info = $stripe->customers->create([
            'description' => 'Veri User',
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $newdata = [
            'name'=> $request->name,
            'email'=> $request->email,
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'country_id'=> $request->country_id,
            'password'=> bcrypt($request->password),
            'phone'=> $request->phone,
			'language'=> $request->language,
            'stripe_cust_id' => $customer_info->id,
        ];

        $user = User::create($newdata);

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

    public function email_verification(Request $request)
    {
        $data = $request->all();
        $user_id = $data['user_id'];
        $ucode = $data['verification_code'];

        $userinfo = User::where('id', $user_id)->first();
        if(!$userinfo)
        {
            return response()->json(['error' => 'User not found']);
        }
        if($userinfo->verification_code !== $ucode)
        {
            return response()->json(['error' => 'Verification code doesn\'t match']);
        }
        if($userinfo->verification_code_expiry < time())
        {
            return response()->json(['error' => 'Verification code expired']);
        }
        return response()->json(['error' => 'Success']);
    }

    public function login_required()
    {
        return response()->json(['error' => 'Login is required.']);
    }
}