<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;

class AuthWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'confirmPassword');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'telepon' => ['nullable', 'string', 'max:15'],
            ]);

            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telepon' => $validated['telepon'] ?? null,
                'role' => 'anggota',
            ]);

            Auth::login($user);
            // Redirect dengan pesan sukses
            return redirect(RouteServiceProvider::HOME)->with('success', 'Registrasi berhasil! Selamat datang.');
        } catch (ValidationException $e) {
            // Jika validasi gagal, kembali ke halaman register dengan input dan error
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            return back()->with('error', 'Terjadi kesalahan saat pendaftaran: ' . $e->getMessage())->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showConfirmForm()
    {
        return view('auth.confirm');
    }

    public function confirmPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [trans('auth.password')],
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function showForgotPasswordForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $response = Password::sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', trans($response))
            : back()->withErrors(['email' => trans($response)]);
    }

    public function showResetPasswordForm(Request $request)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $request->route('token'), 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->password = Hash::make($request->password);
                $user->setRememberToken('');
                $user->save();

                event(new PasswordReset($user));
                Auth::guard()->login($user);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? redirect(RouteServiceProvider::HOME)->with('status', trans($response))
            : back()->withErrors(['email' => trans($response)]);
    }
}
