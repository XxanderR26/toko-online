<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // VALIDASI
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ],
        [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',

            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter'
        ]
        );

        // SIMPAN USER
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // RESPONSE JSON
        return response()->json([
            'message' => 'Register berhasil',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        // VALIDASI
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // CEK USER
        $user = User::where('email', $request->email)->first();

        // CEK PASSWORD
        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        // BUAT TOKEN
        $token = $user->createToken('auth_token')->plainTextToken;

        // RESPONSE
        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }
}
