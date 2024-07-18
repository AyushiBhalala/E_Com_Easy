<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',  
            'role' => 'required|string|in:user,supplier,admin',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        // Redirect to login page after successful registration
        return redirect()->route('login.form')->with('success', 'Registration successful! Please log in.');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return $this->redirectBasedOnRole($user);
        }

        return redirect()->route('login.form')->withErrors(['email' => 'Invalid credentials']);
    }

    protected function redirectBasedOnRole($user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('supplier')) {
            return redirect()->route('supplier.dashboard');
        } elseif ($user->hasRole('user')) {
            return redirect()->route('user.dashboard');
        }

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page after logout
        return redirect()->route('login.form')->with('success', 'You have been logged out successfully.');
    }
    public function showChangePasswordForm(){
        return view('supplier.changePassword');
    }   
    public function changePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        $user = Auth::user();
        // \Log::info('User ID: ' . $user->id);
        // \Log::info('Stored Hashed Password: ' . $user->password);
        if (!Hash::check($request->old_password, $user->password)) {
            // \Log::info('Provided Old Password: ' . $request->old_password);
            return response()->json([
                'status' => false,
                'errors' => ['old_password' => ['Incorrect old password']],
            ]);
        }
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Password successfully changed.'
        ]);
    }
    
    
}
