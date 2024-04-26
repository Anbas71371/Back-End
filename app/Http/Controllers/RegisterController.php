<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Hash; // Tambahkan use Hash; untuk menggunakan Hash::make()

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input sebelum menyimpan data pengguna baru
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first() // Mengambil pesan error pertama dari validator
            ], 400); // Kode status 400 untuk Bad Request
        }

        // Enkripsi password sebelum menyimpan pengguna baru
        $user = User::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
            'no_rekening' => $request->no_rekening,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'user' => $user
        ], 201); // Kode status 201 untuk Created
    }

    public function show()
    {
        $names = User::pluck('nama'); // Mengambil kolom 'nama' dari tabel 'users'

        return response()->json($names);
    }

    public function data()
    {
        $data = User::all();
        return response()->json($data);
    }

    public function userId($id)
    {
        // Mengambil data pengguna berdasarkan ID yang diberikan
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404); // Kode status 404 untuk Not Found
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function getLoggedInUserId()
    {
        // Mengambil ID pengguna yang sedang masuk saat ini
        $loggedInUserId = Auth::id();

        if (!$loggedInUserId) {
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak terautentikasi'
            ], 401); // Kode status 401 untuk Unauthorized
        }

        return response()->json([
            'status' => true,
            'user_id' => $loggedInUserId
        ]);

    }
    public function getUsername($id)
    {
        // Mengambil data pengguna berdasarkan ID yang diberikan
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404); // Kode status 404 untuk Not Found
        }

        return response()->json([
            'status' => true,
            'username' => $user->nama // Mengambil nama pengguna dari data pengguna
        ]);
    }

}
