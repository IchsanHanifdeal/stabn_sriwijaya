<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        $jumlah_mahasiswa = $mahasiswa->count();
        $mahasiswa_terbaru = $mahasiswa->sortByDesc('created_at')->first();
        $waktu_daftar = $mahasiswa_terbaru ? $mahasiswa_terbaru->created_at->format('d-m-Y H:i:s') : null;
        $jumlah_mahasiswa_laki = $mahasiswa->where('jenis_kelamin', 'L')->count();
        $jumlah_mahasiswa_perempuan = $mahasiswa->where('jenis_kelamin', 'P')->count();

        return view('dashboard.mahasiswa', [
            'mahasiswa' => $mahasiswa,
            'jumlah_mahasiswa' => $jumlah_mahasiswa,
            'mahasiswa_terbaru' => $mahasiswa_terbaru ? $mahasiswa_terbaru->name : '-',
            'waktu_daftar' => $waktu_daftar,
            'jumlah_mahasiswa_laki' => $jumlah_mahasiswa_laki,
            'jumlah_mahasiswa_perempuan' => $jumlah_mahasiswa_perempuan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function terima($id_user)
    {
        $user = User::findOrFail($id_user);

        if ($user->role === 'mahasiswa') {
            $user->validasi = 'diterima';
            $user->save();

            return redirect()->back()->with('toast', ['message' => 'Mahasiswa berhasil diterima!', 'type' => 'success']);
        }

        return redirect()->back()->with('toast', ['message' => 'Pengguna bukan mahasiswa!', 'type' => 'error']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function tolak($id_user)
    {
        $user = User::findOrFail($id_user);

        if ($user->role === 'mahasiswa') {
            $user->validasi = 'ditolak';
            $user->save();

            return redirect()->back()->with('toast', ['message' => 'Mahasiswa berhasil ditolak!', 'type' => 'success']);
        }

        return redirect()->back()->with('toast', ['message' => 'Pengguna bukan mahasiswa!', 'type' => 'error']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id_user)
    {
        DB::beginTransaction();

        try {
            $mahasiswa = Mahasiswa::where('id_user', $id_user)->firstOrFail();

            $mahasiswa->delete();

            $user = User::findOrFail($id_user);
            $user->delete();

            DB::commit();

            return redirect()->back()->with('toast', ['message' => 'Mahasiswa berhasil dihapus!', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', ['message' => 'Terjadi kesalahan. Silakan coba lagi nanti.', 'type' => 'error']);
        }
    }
}
