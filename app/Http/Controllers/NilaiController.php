<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.nilai');
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
    public function store(Request $request, $id_nilai)
    {

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
    public function update(Request $request, $id_nilai)
    {
        $validator = Validator::make($request->all(), [
            'nilai' => 'required|numeric|min:0|max:100',
            'komentar' => 'nullable|string',
        ], [
            'nilai.max' => 'Nilai tidak bisa lebih dari 100.',
            'nilai.min' => 'Nilai tidak bisa kurang dari 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $nilai = Nilai::findOrFail($id_nilai);
        $nilai->nilai = $request->input('nilai');
        $nilai->komentar = $request->input('komentar');
        $nilai->keterangan = 'dinilai';
        $nilai->save();

        return redirect()->back()->with('toast', [
            'message' => 'Tugas berhasil dinilai!',
            'type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
