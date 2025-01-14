<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\HelperApi;
use App\Http\Traits\ImageProcessing;
use App\Models\SocialUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HelperApi, ImageProcessing;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);//login, register methods won't go through the api guard
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $user = SocialUser::where('email', $request->email)->first();

            $token = JWTAuth::fromUser($user);

            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }
            return $this->onSuccessWithToken(200, __('site.login_user'), $user, $token);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            $rules= [
                'email' => ['required', 'email', 'max:255', 'unique:social_users'],
                'phone' => ['required','unique:social_users'],
                'city' => ['required'],
                'name' => ['required'],
                'password' => ['required', 'min:6'],
            ];

            $validator = Validator::Make($request->all(), $rules);

            if ($validator->fails()){
                return $this->onError(500, 'validation  error');
            }

            $user = SocialUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'password' => Hash::make($request->password),
            ]);
            $token = JWTAuth::fromUser($user);
            return $this->onSuccessWithToken(200, __('site.create_user'), $user, $token);
        } catch (\Throwable $error) {
            return $this->onError(500, trans('site.server_error'), $error->getMessage());
        }
    }

    public function getaccount()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return $this->onSuccess(200, trans('site.logout_user'));
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60000 //mention the guard name inside the auth fn
        ]);
    }
}
