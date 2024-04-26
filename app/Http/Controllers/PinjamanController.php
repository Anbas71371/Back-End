<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PinjamanController extends Controller
{

    public function __construct()
    {
        // Tambahkan middleware auth:api ke semua metode controller
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        // Dapatkan pengguna yang sedang login
        $user = Auth::user();
    
        // Log informasi user
        Log::info('User saat ini: ' . json_encode($user));
    
        // Periksa apakah pengguna sudah login
        if (!$user) {
            return response()->json(['error' => 'Anda belum login.'], 401);
        }
    
        // Validasi data request
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'besar_pinjaman' => 'required|numeric|min:0',
        ]);
    
        // Buat objek Pinjaman baru
        $pinjaman = new Pinjaman();
        $pinjaman->tanggal = $validatedData['tanggal'];
        $pinjaman->besar_pinjaman = $validatedData['besar_pinjaman'];
        $pinjaman->user_id = $user->id; // Atur ID pengguna yang membuat pinjaman
    
        // Hitung nilai jasa dan total
        $pinjaman->jasa = 2000; // Misalnya, nilai jasa tetap 2000
        $pinjaman->total = $pinjaman->besar_pinjaman + $pinjaman->jasa;
    
        // Simpan pinjaman ke dalam database
        $pinjaman->save();
    
        // Return response sukses
        return response()->json(['message' => 'Peminjaman berhasil disimpan', 'data' => $pinjaman], 201);
    }
    

    public function index($id)
{
    // Dapatkan semua data pinjaman yang terkait dengan ID pengguna tertentu
    $pinjamans = Pinjaman::where('user_id', $id)->get();

    // Return data dalam format JSON
    return response()->json(['data' => $pinjamans], 200);
}


    public function show($id)
    {
        // Dapatkan data pinjaman berdasarkan ID
        $pinjaman = Pinjaman::findOrFail($id);

        // Return data dalam format JSON
        return response()->json(['data' => $pinjaman], 200);
    }

    public function update(Request $request, $id)
    {
        // Validasi data request
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'besar_pinjaman' => 'required|numeric|min:0',
        ]);
    
        // Dapatkan data pinjaman berdasarkan ID
        $pinjaman = Pinjaman::findOrFail($id);
    
        // Perbarui data pinjaman
        $pinjaman->tanggal = $validatedData['tanggal'];
        $pinjaman->besar_pinjaman = $validatedData['besar_pinjaman'];
        
        // Hitung kembali nilai total berdasarkan besar pinjaman dan jasa
        $pinjaman->jasa = 2000; // Misalnya, nilai jasa tetap 2000
        $pinjaman->total = $pinjaman->besar_pinjaman + $pinjaman->jasa;
    
        // Simpan perubahan ke dalam database
        $pinjaman->save();
    
        // Return response sukses
        return response()->json(['message' => 'Data pinjaman berhasil diperbarui', 'data' => $pinjaman], 200);
    }
    public function destroy($id)
    {
        // Dapatkan data pinjaman berdasarkan ID
        $pinjaman = Pinjaman::findOrFail($id);

        // Hapus data pinjaman
        $pinjaman->delete();

        // Return response sukses
        return response()->json(['message' => 'Data pinjaman berhasil dihapus'], 200);
    }
}
