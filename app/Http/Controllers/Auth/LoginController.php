<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function authenticated(Request $request, $user)
    {
        if ($user->status !== User::STATUS_ACTIVE) {
            $this->guard()->logout();
            return back()->with('error', 'Для входа в аккаунт необходимо подтвердить e-mail, перейдя по ссылке, отправленной вам на почту.');
        }
        return redirect()->intended($this->redirectPath());
    }
//    public function login(Request $request)
//    {
//
//        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
//            return response()->json([
//                'success' => true,
//            ]);
//        }
//
//        return response()->json([
//            'success' => false,
//            'error' => 'Неврное имя пользователя или пароль'
//        ]);
//    }

}
