<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Tugas;
use App\Models\Materi;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mahasiswa = Mahasiswa::where('id_user', Auth::user()->id_user)->first();
        $idMahasiswa = $mahasiswa ? $mahasiswa->id_mahasiswa : null;

        $matakuliah = MataKuliah::all();
        $selectedMataKuliah = $request->query('mata_kuliah');

        if ($selectedMataKuliah) {
            $tugas = Tugas::where('id_matakuliah', $selectedMataKuliah)->get();
        } else {
            $tugas = Tugas::all();
        }

        $nilai = Nilai::where('id_mahasiswa', $idMahasiswa)->get()->keyBy('id_tugas');

        $tugas_terbaru = $tugas->sortByDesc('created_at')->first();
        $terakhir_upload = $tugas->sortByDesc('updated_at')->first();

        return view('dashboard.tugas', [
            'matakuliah' => $matakuliah,
            'tugas' => $tugas,
            'tugas_terbaru' => $tugas_terbaru,
            'terakhir_upload' => $terakhir_upload,
            'log_mahasiswa' => $idMahasiswa,
            'nilai' => $nilai,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function hapus_nilai(Request $request, $id_tugas, $id_mahasiswa)
    {
        $tugas = Tugas::find($id_tugas);
        if (!$tugas) {
            return redirect()->back()->withErrors(['tugas' => 'Tugas tidak ditemukan.']);
        }

        $nilai = Nilai::where('id_tugas', $id_tugas)
            ->where('id_mahasiswa', $id_mahasiswa)
            ->first();

        if (!$nilai) {
            return redirect()->back()->withErrors(['nilai' => 'Nilai tidak ditemukan.']);
        }

        if ($nilai->file) {
            $filePath = storage_path('app/public/tugas/pengumpulan/' . $nilai->file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $nilai->delete();

        return redirect()->back()->with('toast', [
            'message' => 'Pengumpulan tugas berhasil ditarik!',
            'type' => 'success',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_tugas' => 'required|string|max:255',
            'mata_kuliah' => 'required|integer|exists:mata_kuliah,id_matakuliah',
            'pertemuan' => 'required|integer|min:1|max:16',
            'deskripsi' => 'required|string',
            'lampiran_tugas' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ], [
            'judul_tugas.required' => 'Judul tugas harus diisi.',
            'judul_tugas.string' => 'Judul tugas harus berupa teks.',
            'judul_tugas.max' => 'Judul tugas maksimal 255 karakter.',
            'mata_kuliah.required' => 'Mata kuliah harus dipilih.',
            'mata_kuliah.integer' => 'Mata kuliah harus berupa angka.',
            'mata_kuliah.exists' => 'Mata kuliah tidak valid.',
            'pertemuan.required' => 'Pertemuan harus diisi.',
            'pertemuan.integer' => 'Pertemuan harus berupa angka.',
            'pertemuan.min' => 'Pertemuan minimal 1.',
            'pertemuan.max' => 'Pertemuan maksimal 16.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'lampiran_tugas.file' => 'Lampiran harus berupa file.',
            'lampiran_tugas.mimes' => 'Lampiran harus berupa file dengan format: pdf, doc, docx, jpg, jpeg, png.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        $savedData = Tugas::create([
            'pertemuan' => $validatedData['pertemuan'],
            'judul_tugas' => $validatedData['judul_tugas'],
            'id_matakuliah' => $validatedData['mata_kuliah'],
            'deskripsi' => $validatedData['deskripsi'],
        ]);

        if ($request->hasFile('lampiran_tugas')) {
            $file = $request->file('lampiran_tugas');
            $fileName = $file->getClientOriginalName();

            $existingFile = Tugas::where('lampiran_tugas', $fileName)->first();

            if ($existingFile) {
                return redirect()->back()->withErrors(['lampiran_tugas' => 'File sudah ada di sistem.'])->withInput();
            }

            $file_path = $file->store('public/tugas');
            $savedData->lampiran_tugas = str_replace('public/', '', $file_path);
            $savedData->save();
        }

        return redirect()->back()->with('toast', [
            'message' => 'Tugas berhasil ditambahkan!',
            'type' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function kumpul(Request $request, $id_tugas)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ], [
            'file.required' => 'File harus diunggah.',
            'file.file' => 'Yang diunggah harus berupa file.',
            'file.mimes' => 'File harus bertipe pdf, doc, docx, jpg, jpeg, atau png.',
            'file.max' => 'File maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mahasiswa = Mahasiswa::where('id_user', Auth::user()->id_user)->first();
        $idMahasiswa = $mahasiswa ? $mahasiswa->id_mahasiswa : null;

        if (!$idMahasiswa) {
            return redirect()->back()->withErrors(['mahasiswa' => 'Data mahasiswa tidak ditemukan.'])->withInput();
        }

        $tugas = Tugas::find($id_tugas);
        if (!$tugas) {
            return redirect()->back()->withErrors(['tugas' => 'Tugas tidak ditemukan.'])->withInput();
        }

        $file_path = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $existingFile = Nilai::where('file', $fileName)->first();
            if ($existingFile) {
                return redirect()->back()->withErrors(['file' => 'File sudah ada di sistem.'])->withInput();
            }

            $file_path = $file->store('public/tugas/pengumpulan');
            $file_path = str_replace('public/', '', $file_path);
        }

        Nilai::create([
            'id_tugas' => $id_tugas,
            'id_matakuliah' => $tugas->id_matakuliah,
            'id_mahasiswa' => $idMahasiswa,
            'tanggal_pengumpulan' => now(),
            'file' => $file_path,
        ]);

        return redirect()->back()->with('toast', [
            'message' => 'Tugas berhasil dikumpulkan!',
            'type' => 'success',
        ]);
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
    public function destroy($id_tugas)
    {
        $tugas = tugas::findOrFail($id_tugas);

        if ($tugas->lampiran_tugas) {
            $filePath = 'public/tugas/' . $tugas->file_tugas;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }

        $tugas->delete();

        return redirect()->back()->with('toast', [
            'message' => 'tugas berhasil dihapus!',
            'type' => 'success'
        ]);
    }
}
