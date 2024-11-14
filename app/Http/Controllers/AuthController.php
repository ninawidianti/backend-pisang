<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:3|confirmed',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'role' => 'nullable|string|in:admin,customer', // Validasi untuk role
        ]);

        \Log::info('Validated Data: ', $validateData);

        // Atur role default ke 'customer' jika tidak disertakan
        $role = $validateData['role'] ?? 'customer';

        $user = User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            'no_hp' => $validateData['no_hp'],
            'alamat' => $validateData['alamat'],
            'role' => $role, // Menyimpan role ke database
        ]);

        return response()->json(['message' => 'User Registered successfully', 'data' => $user]);
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role, // Mengembalikan role
                ]
            ]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    // Fungsi Logout
    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Logout berhasil.']);
        } else {
            return response()->json(['message' => 'Pengguna tidak ditemukan atau belum login.'], 401);
        }
    }
    

}
