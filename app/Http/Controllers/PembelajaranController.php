<?php

namespace App\Http\Controllers;

use App\Models\Silabus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.pembelajaran', [
            'pembelajaran' => Silabus::all(),
            'jumlah_pembelajaran' => Silabus::count(),
            'pembelajaran_terbaru' => Silabus::orderBy('created_at', 'desc')->first(),
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
            'pertemuan' => 'required',
            'nama_silabus' => 'required',
            'deskripsi' => 'required',
            'file_silabus' => 'nullable|file',
            'tipe_file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        $savedData = Silabus::create([
            'pertemuan' => $validatedData['pertemuan'],
            'nama_silabus' => $validatedData['nama_silabus'],
            'deskripsi' => $validatedData['deskripsi'],
            'tipe_file' => $validatedData['tipe_file'],
            'file_silabus' => null,
        ]);

        if ($request->hasFile('file_silabus')) {
            $file = $request->file('file_silabus');
            $fileName = $file->getClientOriginalName();

            $existingFile = Silabus::where('file_silabus', $fileName)->first();

            if ($existingFile) {
                return redirect()->back()->withErrors(['file_silabus' => 'File sudah ada di sistem.'])->withInput();
            }

            $file_path = $file->store('public/pembelajaran');
            $savedData->file_silabus = str_replace('public/', '', $file_path);
            $savedData->save();
        }

        return redirect()->back()->with('toast', [
            'message' => 'Pembelajaran berhasil ditambahkan!',
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
    public function update(Request $request, $id_silabus)
    {
        $validator = Validator::make($request->all(), [
            'pertemuan' => 'required',
            'nama_silabus' => 'required',
            'deskripsi' => 'required',
            'file_silabus' => 'nullable|file',
            'tipe_file' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        $silabus = Silabus::findOrFail($id_silabus);

        $silabus->update([
            'pertemuan' => $validatedData['pertemuan'],
            'nama_silabus' => $validatedData['nama_silabus'],
            'deskripsi' => $validatedData['deskripsi'],
            'tipe_file' => $validatedData['tipe_file'],
        ]);

        if ($request->hasFile('file_silabus')) {
            $file = $request->file('file_silabus');
            $fileName = $file->getClientOriginalName();

            $existingFile = Silabus::where('file_silabus', $fileName)->where('id_silabus', '<>', $id_silabus)->first();

            if ($existingFile) {
                return redirect()->back()->withErrors(['file_silabus' => 'File sudah ada di sistem.'])->withInput();
            }

            $file_path = $file->store('public/pembelajaran');
            $filePath = str_replace('public/', '', $file_path);

            $silabus->file_silabus = $filePath;
            $silabus->save();
        }

        return redirect()->back()->with('toast', [
            'message' => 'Pembelajaran berhasil diperbarui!',
            'type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_silabus)
    {
    $silabus = Silabus::findOrFail($id_silabus);

    if ($silabus->file_silabus) {
        $filePath = 'public/pembelajaran/' . $silabus->file_silabus;
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }
    }

    $silabus->delete();

    return redirect()->back()->with('toast', [
        'message' => 'Pembelajaran berhasil dihapus!',
        'type' => 'success'
    ]);
    }
}
