<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Http\Request;
use App\Core\Auth\Auth;
use App\Core\Session\Session;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('Auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean'
        ]);
        // Attempt login
        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !password_verify($validatedData['password'], $user->password)) {
            session()->flash('error', 'Invalid email or password.');
            return redirect()->back()->with('error', 'Invalid email or password.');
        }


        // Check if user is active
        if ($user->status !== 'active') {
            session()->flash('error', 'Your account is inactive. Please contact administrator.');
            return redirect()->back()->withInput();
        }

        // Login user
        Auth::login($user, $validatedData['remember'] ?? false);


        // Update last login
//        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        // Redirect to intended page or dashboard
        $redirectTo = session()->get('url.intended') ?? route('dashboard');
        session()->remove('url.intended');

        session()->flash('success', 'Welcome back, ' . $user->name . '!');

        return redirect()->to($redirectTo);
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('Auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted'
        ]);

        try {
            // Create new user
            $user = new User();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->password = password_hash($validatedData['password'], PASSWORD_DEFAULT);
            $user->status = 'active';
            $user->role_id = 2; // Default role (e.g., 'user')
            $user->save();

            // Auto login after registration
            Auth::login($user);

            session()->flash('success', 'Registration successful! Welcome to Admin Panel.');

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            session()->flash('error', 'Registration failed. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        Auth::logout();
        session()->flash('success', 'You have been logged out successfully.');

        return redirect()->route('login');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('Auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            // Generate token
            $token = bin2hex(random_bytes(32));

            // Store token in password_resets table
            // Send email with reset link

            session()->flash('success', 'Password reset link has been sent to your email.');

        } catch (\Exception $e) {
            session()->flash('error', 'Unable to send reset link. Please try again.');
        }

        return redirect()->back();
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token)
    {
        return view('Auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        try {
            // Verify token
            // Update password

            session()->flash('success', 'Password has been reset successfully. Please login with your new password.');

            return redirect()->route('login');

        } catch (\Exception $e) {
            session()->flash('error', 'Unable to reset password. Please try again.');
            return redirect()->back();
        }
    }
}
