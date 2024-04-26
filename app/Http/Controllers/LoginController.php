<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function getUser(Request $request)
    {
        $user = Auth::user(); // Mendapatkan data pengguna yang sedang login
        return response()->json(['user' => $user], 200);

        
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nama' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Ambil user berdasarkan nama
        $user = User::where('nama', $credentials['nama'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            // Pesan error yang jelas jika autentikasi gagal
            return response()->json(['status' => false, 'message' => 'Nama atau password salah'], 401);
        }

        // Generate token jika autentikasi berhasil
        $token = $user->createToken('MyApp')->accessToken;

        return response()->json(['status' => true, 'message' => 'Success', 'token' => $token], 200);
        
    }
    // LoginController.php

    public function logout(Request $request)
    {
        // Pastikan pengguna sudah terotentikasi sebelum melakukan logout
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Lakukan proses logout dengan mencabut token pengguna
        $request->user()->token()->revoke();
    
        return response()->json(['message' => 'Successfully logged out']);
    }
    
}
