<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogon()
    {
        return view('auth.logon');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            switch ($user->role) {
                case 'waste_company':
                    return redirect()->route('waste-company.dashboard');
                case 'scrap_dealer':
                    return redirect()->route('scrap-dealer.dashboard');
                case 'recycling_plant':
                    return redirect()->route('recycling-plant.dashboard');
                case 'delivery_staff':
                    return redirect()->route('delivery.index');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:waste_company,scrap_dealer,recycling_plant,delivery_staff',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Họ và tên là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
            'phone.required' => 'Số điện thoại là bắt buộc',
            'role.required' => 'Vui lòng chọn vai trò',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
            'terms.required' => 'Bạn phải đồng ý với điều khoản và chính sách',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'description' => $request->description,
        ]);

        Auth::login($user);

        // Redirect with success message
        session()->flash('success', 'Đăng ký thành công! Chào mừng bạn đến với hệ thống.');

        // Redirect based on user role
        switch ($user->role) {
            case 'waste_company':
                return redirect()->route('waste-company.dashboard');
            case 'scrap_dealer':
                return redirect()->route('scrap-dealer.dashboard');
            case 'recycling_plant':
                return redirect()->route('recycling-plant.dashboard');
            case 'delivery_staff':
                return redirect()->route('delivery.index');
            default:
                return redirect()->route('index');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Flash message for successful logout
        $request->session()->flash('logout_success', true);

        return redirect('/logon');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Logic to send password reset email would go here
        // For now, just redirect back with success message

        return back()->with('status', 'Chúng tôi đã gửi liên kết đặt lại mật khẩu đến email của bạn!');
    }
}
