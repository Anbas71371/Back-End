<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function getUserById($id)
    {
        // Mendapatkan data pengguna berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    public function getLoggedInUser(Request $request)
    {
        // Mendapatkan data pengguna yang sedang login berdasarkan token
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    public function update(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'nama' => 'required|string',
            'nip' => 'required|string',
            'no_hp' => 'requi   red|string',
            'no_rekening' => 'required|string',
            'alamat' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Pastikan user terotentikasi sebelum melakukan pembaruan
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Update data profil user
        $user->nama = $validatedData['nama'];
        $user->nip = $validatedData['nip'];
        $user->no_hp = $validatedData['no_hp'];
        $user->no_rekening = $validatedData['no_rekening'];
        $user->alamat = $validatedData['alamat'];
        $user->password = bcrypt($validatedData['password']); // Menggunakan bcrypt untuk menyimpan password yang di-hash

        // Simpan perubahan
        $user->save();

        return response()->json(['message' => 'Profil berhasil diperbarui'], 200);
    }
    public function getProfile()
    {
        $user = Auth::user(); // Mengambil user yang sedang login, pastikan sudah di-authenticate
        return response()->json([
            'nama' => $user->nama,
            'nip' => $user->nip,
            'no_hp' => $user->no_hp,
            'no_rekening' => $user->no_rekening,
            'alamat' => $user->alamat,
            // Tambahkan atribut lain yang ingin Anda tampilkan di profil
        ]);
    }
}
