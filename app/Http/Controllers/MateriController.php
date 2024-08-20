<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedMataKuliah = $request->query('mata_kuliah');

        if ($selectedMataKuliah) {
            $materi = Materi::where('id_matakuliah', $selectedMataKuliah)->get();
        } else {
            $materi = Materi::all();
        }

        return view('dashboard.materi', [
            'matakuliah' => MataKuliah::all(),
            'materi' => $materi,
            'jumlah_materi' => Materi::count(),
            'materi_terbaru' => Materi::orderBy('created_at', 'desc')->first(),
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
        $validator = Validator::make($request->all(), [
            'pertemuan' => 'required|integer|min:1|max:16',
            'judul_materi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'id_matakuliah' => 'required|string',
            'file_materi' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4|max:20480',
            'tipe_file' => 'required|string|in:gambar,dokumen,video',
        ], [
            'pertemuan.required' => 'Pertemuan harus diisi.',
            'pertemuan.integer' => 'Pertemuan harus berupa angka.',
            'pertemuan.min' => 'Pertemuan tidak boleh kurang dari 1.',
            'pertemuan.max' => 'Pertemuan tidak boleh lebih dari 16.',
            'judul_materi.required' => 'Judul materi harus diisi.',
            'judul_materi.string' => 'Judul materi harus berupa teks.',
            'judul_materi.max' => 'Judul materi tidak boleh lebih dari 255 karakter.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'file_materi.file' => 'File materi harus berupa file.',
            'file_materi.mimes' => 'File materi harus berupa file dengan tipe: jpg, jpeg, png, pdf, atau mp4.',
            'file_materi.max' => 'File materi tidak boleh lebih dari 20 MB.',
            'tipe_file.required' => 'Tipe file harus diisi.',
            'tipe_file.string' => 'Tipe file harus berupa teks.',
            'tipe_file.in' => 'Tipe file harus salah satu dari: gambar, dokumen, video.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('toast', [
                'message' => 'Ada kesalahan dalam formulir yang Anda isi. Silakan periksa kembali.',
                'type' => 'error'
            ]);
        }

        DB::beginTransaction();

        try {
            $validatedData = $validator->validated();

            $savedData = Materi::create([
                'pertemuan' => $validatedData['pertemuan'],
                'judul_materi' => $validatedData['judul_materi'],
                'deskripsi' => $validatedData['deskripsi'],
                'tipe_materi' => $validatedData['tipe_file'],
                'id_matakuliah' => $validatedData['id_matakuliah'],
                'file_materi' => null,
            ]);

            if ($request->hasFile('file_materi')) {
                $file = $request->file('file_materi');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $existingFile = Materi::where('file_materi', $fileName)->first();
                if ($existingFile) {
                    throw new \Exception('File sudah ada di sistem.');
                }

                $filePath = $file->storeAs('public/materi', $fileName);
                $savedData->file_materi = str_replace('public/', '', $filePath);
                $savedData->save();
            }

            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Materi berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['file_materi' => $e->getMessage()])->withInput()->with('toast', [
                'message' => $e->getMessage(),
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
    public function update(Request $request, $id_materi)
    {
        // Validasi input dari form
        $validator = Validator::make($request->all(), [
            'pertemuan' => 'required|integer|min:1|max:16',
            'judul_materi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_materi' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4|max:20480',
            'tipe_file' => 'required|string|in:gambar,dokumen,video',
        ], [
            'pertemuan.required' => 'Pertemuan harus diisi.',
            'pertemuan.integer' => 'Pertemuan harus berupa angka.',
            'pertemuan.min' => 'Pertemuan tidak boleh kurang dari 1.',
            'pertemuan.max' => 'Pertemuan tidak boleh lebih dari 16.',
            'judul_materi.required' => 'Judul materi harus diisi.',
            'judul_materi.string' => 'Judul materi harus berupa teks.',
            'judul_materi.max' => 'Judul materi tidak boleh lebih dari 255 karakter.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'file_materi.file' => 'File materi harus berupa file.',
            'file_materi.mimes' => 'File materi harus berupa file dengan tipe: jpg, jpeg, png, pdf, atau mp4.',
            'file_materi.max' => 'File materi tidak boleh lebih dari 20 MB.',
            'tipe_file.required' => 'Tipe file harus diisi.',
            'tipe_file.string' => 'Tipe file harus berupa teks.',
            'tipe_file.in' => 'Tipe file harus salah satu dari: gambar, dokumen, video.',
        ]);

        // Cek jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('toast', [
                'message' => 'Ada kesalahan dalam formulir yang Anda isi. Silakan periksa kembali.',
                'type' => 'error'
            ]);
        }

        DB::beginTransaction();

        try {
            $validatedData = $validator->validated();

            $materi = Materi::findOrFail($id_materi);

            $materi->update([
                'pertemuan' => $validatedData['pertemuan'],
                'judul_materi' => $validatedData['judul_materi'],
                'deskripsi' => $validatedData['deskripsi'],
                'tipe_materi' => $validatedData['tipe_file'],
            ]);

            if ($request->hasFile('file_materi')) {
                $file = $request->file('file_materi');
                $fileName = time() . '_' . $file->getClientOriginalName();

                $existingFile = Materi::where('file_materi', $fileName)->where('id_materi', '!=', $id_materi)->first();
                if ($existingFile) {
                    throw new \Exception('File sudah ada di sistem.');
                }

                if ($materi->file_materi) {
                    $oldFilePath = 'public/materi/' . $materi->file_materi;
                    if (file_exists(storage_path('app/' . $oldFilePath))) {
                        unlink(storage_path('app/' . $oldFilePath));
                    }
                }

                $filePath = $file->storeAs('public/materi', $fileName);
                $materi->file_materi = str_replace('public/', '', $filePath);
                $materi->save();
            }

            DB::commit();

            return redirect()->back()->with('toast', [
                'message' => 'Materi berhasil diperbarui!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['file_materi' => $e->getMessage()])->withInput()->with('toast', [
                'message' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_materi)
    {
        $materi = Materi::findOrFail($id_materi);

        if ($materi->file_materi) {
            $filePath = 'public/materi/' . $materi->file_materi;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }

        $materi->delete();

        return redirect()->back()->with('toast', [
            'message' => 'Materi berhasil dihapus!',
            'type' => 'success'
        ]);
    }
}
