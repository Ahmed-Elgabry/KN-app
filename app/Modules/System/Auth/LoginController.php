<?php

namespace App\Modules\System\Auth;

use App\Http\Requests\CodeRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Auth;
use App\Modules\System\SystemController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use App\Libs\GoogleAuthenticator;
use Illuminate\Support\Facades\Hash;

class LoginController extends SystemController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    function showLoginForm()
    {
        return parent::view('auth.login');
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/system';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

    public function guard()
    {
        return \Auth::guard('user');
    }

    public function login(Request $request)
    {
       // dd('asd');
        $user_query = User::select(['email', 'two_fa_secret','password'])->where('email', $request->email)
            ->first();
        if ($user_query ) {
            session()->put('user', $user_query);
            $ga = new GoogleAuthenticator();
            if (empty($user_query->two_fa_secret)) {
                $secret = $ga->createSecret();
                User::where('email', $user_query->email)->update(['two_fa_secret' => $secret]);
            } else {
                $secret = $user_query->two_fa_secret;
            }

            $secret = $user_query->two_fa_secret;

            $qrCodeUrl = $ga->getQRCode($secret, 'KN', $user_query->email);

            return $this->success(__('Enter Code'),
                ['first_time' => empty($user_query->two_fa_secret) ? 1 : 0,'qrCodeUrl' => $qrCodeUrl,'email' => $user_query->email]);
        } else {
            return $this->fail(__('Wrong Email or Password'));
        }
    }



    public function verifiyCode(CodeRequest $request)
    {
        $session_user = session()->get('user');
        $user = User::where('email', $session_user->email)->first();
        $ga = new GoogleAuthenticator();
        $checkResult = $ga->verifyCode($user->two_fa_secret, $request->code, 2); // 2 = 2*30sec clock tolerance
       /* if (!$checkResult) {
            return $this->fail(__('Code is Wrong'));
        }*/

        \Auth::guard('user')->loginUsingId($user->id);

        $route = auth('user')->user()->permission_group->new_admin_default_route ? route(auth('user')->user()->permission_group->new_admin_default_route) : route('system.dashboard');

        return $this->success(__('Logged In successfully'), ['url' => $route]);

    }
    protected function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect('/system/login');
    }
}
