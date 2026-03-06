<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ImapAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * @var ImapAuthService
     */
    protected $imapAuthService;

    /**
     * Create a new controller instance.
     *
     * @param ImapAuthService $imapAuthService
     */
    public function __construct(ImapAuthService $imapAuthService)
    {
        $this->imapAuthService = $imapAuthService;
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($this->imapAuthService->authenticate($credentials['email'], $credentials['password'])) {
            // Find or create local user
            $user = User::updateOrCreate(
                ['email' => $credentials['email']],
                [
                    'name' => $credentials['email'], // Defaulting name to email
                    'password' => Hash::make(Str::random(32)), // Random password, not used for IMAP auth
                ]
            );

            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
