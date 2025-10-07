<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerView()
    {
        return view('pages.auth.register', [
            'title' => 'Register'
        ]);
    }

    public function loginView()
    {
        return view('pages.auth.login', [
            'title' => 'Login'
        ]);
    }

    public function registerStore(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email'       => 'required|email|unique:user,email',
            'password'    => 'required|string|min:6',
            'alamat'      => 'required',
            'no_telepon'  => 'required'
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'no_telepon.required' => 'Nomor Telepon harus diisi'
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silahkan login');
    }

    public function loginStore(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password harus diisi',
        ]);

        $remember = $request->has('remember');
        if (!Auth::attempt($request->only('email', 'password'), $remember)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        if (Auth::user()->hasRole(['admin', 'karyawan'])) {
            return redirect()->route('dashboard.home')->with('success', 'Selamat datang, ' . Auth::user()->nama . '!');
        }
        return redirect()->route('frontend.home')->with('success', 'Selamat datang, ' . Auth::user()->nama . '!');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
