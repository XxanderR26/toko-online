<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // VALIDASI
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

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
}
