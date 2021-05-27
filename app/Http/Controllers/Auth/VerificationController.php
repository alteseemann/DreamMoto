<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\User;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/';

    public function __construct()
    {

    }
    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first()) {
            return redirect($this->redirectTo)
                ->with('error', 'Sorry your link cannot be identified.');
        }

        $user->status = User::STATUS_ACTIVE;
        $user->verify_token = null;
        $user->save();

        return redirect($this->redirectTo)
            ->with('success', 'Your e-mail is verified. You can now login.');
    }
}
