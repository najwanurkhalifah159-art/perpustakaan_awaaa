<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            session([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);
            $role = ucfirst($user->role);
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password salah! Admin: admin@example.com / admin | User: user@example.com / user']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Only users can register
        ]);

        return redirect()->route('login')->with('success', 'Daftar berhasil! Silakan login.');
    }

    public function dashboard()
    {
        if (!session()->has('user')) {
            return redirect()->route('login');
        }
        
        $userRole = session('user.role');
        $books = \App\Models\Book::count();
        $activeLoans = \App\Models\Loan::whereNull('return_date')->where('status', 'approved')->count();
        $pendingLoans = $userRole === 'admin' ? \App\Models\Loan::where('status', 'pending')->count() : 0;
        
        return view('dashboard', compact('books', 'activeLoans', 'pendingLoans', 'userRole'));
    }

    public function logout()
    {
        session()->forget(['user']);
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}


