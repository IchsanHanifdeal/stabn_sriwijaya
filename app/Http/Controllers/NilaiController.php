<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\RekapNilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rekap_nilai = RekapNilai::with('mahasiswa', 'matakuliah')->get();

        $rekap_nilai->transform(function ($item) {
            $nilai_akhir = ($item->nilai_kuis * 0.15) +
                ($item->nilai_tugas * 0.10) +
                ($item->nilai_uts * 0.35) +
                ($item->nilai_uas * 0.40);

            if ($nilai_akhir >= 90) {
                $grade = 'A';
            } elseif ($nilai_akhir >= 85) {
                $grade = 'A-';
            } elseif ($nilai_akhir >= 80) {
                $grade = 'B+';
            } elseif ($nilai_akhir >= 75) {
                $grade = 'B';
            } elseif ($nilai_akhir >= 70) {
                $grade = 'B-';
            } elseif ($nilai_akhir >= 65) {
                $grade = 'C+';
            } elseif ($nilai_akhir >= 60) {
                $grade = 'C';
            } elseif ($nilai_akhir >= 55) {
                $grade = 'C-';
            } elseif ($nilai_akhir >= 40) {
                $grade = 'D';
            } else {
                $grade = 'E';
            }

            $item->nilai_akhir = $nilai_akhir;
            $item->grade = $grade;

            return $item;
        });

        $nilai_tertinggi = $rekap_nilai->max('nilai_akhir');
        $peroleh_nilai_tertinggi = $rekap_nilai->firstWhere('nilai_akhir', $nilai_tertinggi)->first();

        $rata_rata_tertinggi = $rekap_nilai->filter(function ($item) use ($nilai_tertinggi) {
            return $item->nilai_akhir == $nilai_tertinggi;
        })->avg('nilai_akhir');

        return view('dashboard.nilai', [
            'matakuliah' => MataKuliah::all(),
            'mahasiswa' => Mahasiswa::all(),
            'rekap_nilai' => $rekap_nilai,
            'peroleh_nilai_tertinggi' => $peroleh_nilai_tertinggi,
            'rata_rata_tertinggi' => $rata_rata_tertinggi,
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'mata_kuliah' => 'required|exists:mata_kuliah,id_matakuliah',
            'nilai_kuis' => 'nullable|integer|min:0|max:100',
            'nilai_tugas' => 'nullable|integer|min:0|max:100',
            'nilai_uts' => 'nullable|integer|min:0|max:100',
            'nilai_uas' => 'nullable|integer|min:0|max:100',
        ], [
            'mahasiswa.required' => 'Mahasiswa harus dipilih.',
            'mahasiswa.exists' => 'Mahasiswa yang dipilih tidak ada.',
            'mata_kuliah.required' => 'Mata kuliah harus dipilih.',
            'mata_kuliah.exists' => 'Mata kuliah yang dipilih tidak ada.',
            'nilai_kuis.integer' => 'Nilai kuis harus berupa angka.',
            'nilai_kuis.min' => 'Nilai kuis tidak boleh kurang dari 0.',
            'nilai_kuis.max' => 'Nilai kuis tidak boleh lebih dari 100.',
            'nilai_tugas.integer' => 'Nilai tugas harus berupa angka.',
            'nilai_tugas.min' => 'Nilai tugas tidak boleh kurang dari 0.',
            'nilai_tugas.max' => 'Nilai tugas tidak boleh lebih dari 100.',
            'nilai_uts.integer' => 'Nilai UTS harus berupa angka.',
            'nilai_uts.min' => 'Nilai UTS tidak boleh kurang dari 0.',
            'nilai_uts.max' => 'Nilai UTS tidak boleh lebih dari 100.',
            'nilai_uas.integer' => 'Nilai UAS harus berupa angka.',
            'nilai_uas.min' => 'Nilai UAS tidak boleh kurang dari 0.',
            'nilai_uas.max' => 'Nilai UAS tidak boleh lebih dari 100.',
        ]);

        try {
            $exists = RekapNilai::where('id_mahasiswa', $validatedData['mahasiswa'])
                ->where('id_matakuliah', $validatedData['mata_kuliah'])
                ->exists();

            if ($exists) {
                return redirect()->back()->with('toast', [
                    'message' => 'Nilai untuk mahasiswa dan mata kuliah ini sudah ada.',
                    'type' => 'error'
                ]);
            }

            DB::beginTransaction();

            RekapNilai::create([
                'id_mahasiswa' => $validatedData['mahasiswa'],
                'id_matakuliah' => $validatedData['mata_kuliah'],
                'nilai_kuis' => $validatedData['nilai_kuis'] ?? null,
                'nilai_tugas' => $validatedData['nilai_tugas'] ?? null,
                'nilai_uts' => $validatedData['nilai_uts'] ?? null,
                'nilai_uas' => $validatedData['nilai_uas'] ?? null,
            ]);

            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Nilai berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('toast', [
                'message' => 'Gagal menyimpan nilai. Silakan coba lagi.',
                'type' => 'error'
            ]);
        }
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
    public function update_rekap(Request $request, $id_rekap_nilai)
    {
        $validatedData = $request->validate([
            'nilai_kuis' => 'nullable|integer|min:0|max:100',
            'nilai_tugas' => 'nullable|integer|min:0|max:100',
            'nilai_uts' => 'nullable|integer|min:0|max:100',
            'nilai_uas' => 'nullable|integer|min:0|max:100',
        ], [
            'nilai_kuis.max' => 'Nilai kuis tidak boleh lebih dari 100.',
            'nilai_tugas.max' => 'Nilai tugas tidak boleh lebih dari 100.',
            'nilai_uts.max' => 'Nilai UTS tidak boleh lebih dari 100.',
            'nilai_uas.max' => 'Nilai UAS tidak boleh lebih dari 100.',
        ]);

        $rekapNilai = RekapNilai::findOrFail($id_rekap_nilai);
        $rekapNilai->update($validatedData);

        return redirect()->back()->with('toast', [
            'message' => 'Nilai berhasil diperbarui!',
            'type' => 'success'
        ])->withInput();
    }

    public function destroy($id_rekap_nilai)
    {
        RekapNilai::destroy($id_rekap_nilai);

        return redirect()->back()->with('toast', [
            'message' => 'Nilai berhasil dihapus!',
            'type' => 'success'
        ]);
    }
}
