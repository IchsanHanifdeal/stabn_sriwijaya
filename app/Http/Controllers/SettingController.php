<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mataKuliah = MataKuliah::all();
        $jurusan = Jurusan::all();

        return view('dashboard.setting', [
            'jumlah_mata_kuliah' => MataKuliah::count(),
            'jumlah_jurusan' => Jurusan::count(),
            'mataKuliah' => $mataKuliah,
            'jurusan' => $jurusan
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeJurusan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10',
        ]);

        Jurusan::create([
            'nama_jurusan' => $request->name,
            'kode_jurusan' => $request->kode_jurusan,
        ]);

        return redirect()->back()->with('toast', [
            'message' => 'Jurusan berhasil ditambahkan!',
            'type' => 'success'
        ]);
    }

    public function storeMataKuliah(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MataKuliah::create([
            'mata_kuliah' => $request->name,
        ]);

        return redirect()->back()->with('toast', [
            'message' => 'Mata Kuliah berhasil ditambahkan!',
            'type' => 'success'
        ]);
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
    public function updateJurusan(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10',
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update([
            'nama_jurusan' => $request->name,
            'kode_jurusan' => $request->kode_jurusan,
        ]);

        return redirect()->back()->with('toast', [
            'message' => 'Jurusan berhasil diperbarui!',
            'type' => 'success'
        ]);
    }

    public function updateMataKuliah(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->update([
            'mata_kuliah' => $request->name,
        ]);

        return redirect()->back()->with('toast', [
            'message' => 'Mata Kuliah berhasil diperbarui!',
            'type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        return redirect()->back()->with('toast', [
            'message' => 'Jurusan berhasil dihapus!',
            'type' => 'success'
        ]);
    }

    public function destroyMataKuliah($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->delete();

        return redirect()->back()->with('toast', [
            'message' => 'Mata Kuliah berhasil dihapus!',
            'type' => 'success'
        ]);
    }
}
