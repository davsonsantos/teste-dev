<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function create()
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::validate($credentials)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if ($user->enable_2fa) {
            $code = rand(100000, 999999);
            $user->update([
                'two_factor_code' => $code,
                'two_factor_expires_at' => now()->addMinutes(10),
            ]);

            $request->session()->put('auth.2fa.id', $user->id);
            $request->session()->put('auth.2fa.remember', $request->boolean('remember'));

            Mail::to($user->email)->send(new TwoFactorCodeNotification($user->name, $code));

            return redirect()->route('two-factor');
        }

        Auth::login($user, $request->boolean('remember'));
        $user = Auth::user();
        $request->session()->regenerate();

        if ($user->role == 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function twoFactor()
    {
        if (!session()->has('auth.2fa.id')) {
            return redirect()->route('login');
        }

        return Inertia::render('Auth/TwoFactor');
    }

    public function twoFactorStore(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);
        $userId = session()->get('auth.2fa.id');
        $user = User::findOrFail($userId);

        if ($request->code !== $user->two_factor_code || now()->greaterThan($user->two_factor_expires_at)) {
            throw ValidationException::withMessages([
                'code' => 'O código informado é inválido ou expirou.',
            ]);
        }

        $user->update(['two_factor_code' => null, 'two_factor_expires_at' => null]);

        Auth::login($user, session()->get('auth.2fa.remember'));

        $request->session()->forget(['auth.2fa.id', 'auth.2fa.remember']);
        $request->session()->regenerate();

        if ($user->role == 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
