<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosen = dosen::all();
        $jumlah_dosen = $dosen->count();
        $dosen_terbaru = $dosen->sortByDesc('created_at')->first();
        $waktu_daftar = $dosen_terbaru ? $dosen_terbaru->created_at->format('d-m-Y H:i:s') : null;
        $jumlah_dosen_laki = $dosen->where('jenis_kelamin', 'L')->count();
        $jumlah_dosen_perempuan = $dosen->where('jenis_kelamin', 'P')->count();

        return view('dashboard.dosen', [
            'dosen' => Dosen::all(),
            'jumlah_dosen' => $jumlah_dosen,
            'dosen_terbaru' => $dosen_terbaru ? $dosen_terbaru->name : '-',
            'waktu_daftar' => $waktu_daftar,
            'jumlah_dosen_laki' => $jumlah_dosen_laki,
            'jumlah_dosen_perempuan' => $jumlah_dosen_perempuan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function terima($id_user)
    {
        $user = User::findOrFail($id_user);

        if ($user->role === 'dosen') {
            $user->validasi = 'diterima';
            $user->save();

            return redirect()->back()->with('toast', ['message' => 'Dosen berhasil diterima!', 'type' => 'success']);
        }

        return redirect()->back()->with('toast', ['message' => 'Pengguna bukan dosen!', 'type' => 'error']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function tolak($id_user)
    {
        $user = User::findOrFail($id_user);

        if ($user->role === 'dosen') {
            $user->validasi = 'ditolak';
            $user->save();

            return redirect()->back()->with('toast', ['message' => 'Dosen berhasil ditolak!', 'type' => 'success']);
        }

        return redirect()->back()->with('toast', ['message' => 'Pengguna bukan dosen!', 'type' => 'error']);
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
            $dosen = dosen::where('id_user', $id_user)->firstOrFail();

            $dosen->delete();

            $user = User::findOrFail($id_user);
            $user->delete();

            DB::commit();

            return redirect()->back()->with('toast', ['message' => 'dosen berhasil dihapus!', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', ['message' => 'Terjadi kesalahan. Silakan coba lagi nanti.', 'type' => 'error']);
        }
    }
}
