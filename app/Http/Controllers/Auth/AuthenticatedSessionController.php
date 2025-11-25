<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required'],
        ]);

        // Validate reCAPTCHA
        if (!$this->validateRecaptcha($request->input('g-recaptcha-response'))) {
            throw ValidationException::withMessages([
                'username' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
            ]);
        }

        $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([
            $loginField => $credentials['username'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'country_code' => ['nullable', 'string', 'max:5'],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
            'g-recaptcha-response' => ['required'],
        ]);

        // Validate reCAPTCHA
        if (!$this->validateRecaptcha($request->input('g-recaptcha-response'))) {
            throw ValidationException::withMessages([
                'email' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
            ]);
        }

        $phoneNumber = trim($data['phone']);

        if (! empty($data['country_code'])) {
            $phoneNumber = trim($data['country_code']) . preg_replace('/\s+/', '', $phoneNumber);
        }

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $phoneNumber,
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        throw ValidationException::withMessages([
            'username' => __('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey($request)),
                'minutes' => ceil(RateLimiter::availableIn($this->throttleKey($request)) / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(strtolower($request->input('username')).'|'.$request->ip());
    }

    /**
     * Validate Google reCAPTCHA response
     */
    protected function validateRecaptcha($recaptchaResponse): bool
    {
        $secretKey = config('services.recaptcha.secret_key');
        
        if (empty($secretKey)) {
            // If no secret key is configured, skip validation (for development)
            return true;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => request()->ip()
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $resultJson = json_decode($result);

        return $resultJson->success ?? false;
    }
}
