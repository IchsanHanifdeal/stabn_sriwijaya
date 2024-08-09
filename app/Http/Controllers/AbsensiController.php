<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $date = $request->input('tanggal', Carbon::now()->format('Y-m-d'));

        if ($user->role === 'dosen') {
            $absen = Absensi::whereDate('tanggal', $date)->get();
        } else {
            $absen = Absensi::whereDate('tanggal', $date)
                ->where('id_user', $user->id_user)
                ->get();
        }

        $jumlah_mahasiswa_hadir = Absensi::whereDate('tanggal', $date)
            ->where('status', 'hadir')
            ->count();
        $jumlah_mahasiswa_sakit = Absensi::whereDate('tanggal', $date)
            ->where('status', 'sakit')
            ->count();
        $jumlah_mahasiswa_izin = Absensi::whereDate('tanggal', $date)
            ->where('status', 'izin')
            ->count();
        $jumlah_mahasiswa_alfa = Absensi::whereDate('tanggal', $date)
            ->where('status', 'alpa')
            ->count();

        return view('dashboard.absensi', [
            'absen' => $absen,
            'jumlah_mahasiswa_hadir' => $jumlah_mahasiswa_hadir,
            'jumlah_mahasiswa_sakit' => $jumlah_mahasiswa_sakit,
            'jumlah_mahasiswa_izin' => $jumlah_mahasiswa_izin,
            'jumlah_mahasiswa_alfa' => $jumlah_mahasiswa_alfa,
            'date' => $date,
            'hari' => Carbon::now()->locale('id')->dayName,
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
        $request->validate([
            'nama' => 'required|string',
            'waktu' => 'required|date_format:H:i',
            'tempat' => 'required|string|max:255',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
            'bukti_absen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pertemuan' => 'required|integer|min:1|max:16',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $existingAbsensi = Absensi::where('id_user', Auth::user()->id_user)
            ->where('pertemuan', $request->pertemuan)
            ->first();

        if ($existingAbsensi) {
            return redirect()->back()->withErrors(['pertemuan' => 'Pertemuan ini sudah diisi.'])->withInput()->with('toast', [
                'message' => 'Pertemuan ini sudah diisi.',
                'type' => 'error'
            ]);
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('bukti_absen')) {
                $fileName = time() . '.' . $request->bukti_absen->extension();
                $request->bukti_absen->storeAs('public/absensi', $fileName);
            } else {
                $fileName = null;
            }

            $absensi = new Absensi();
            $absensi->id_user = Auth::user()->id_user;
            $absensi->tanggal = now()->format('Y-m-d');
            $absensi->waktu = $request->waktu;
            $absensi->lokasi = $request->tempat;
            $absensi->latitude = $request->latitude;
            $absensi->longitude = $request->longitude;
            $absensi->foto = $fileName;
            $absensi->status = $request->status;
            $absensi->pertemuan = $request->pertemuan;
            $absensi->keterangan = $request->keterangan;

            $absensi->save();

            DB::commit();
            return redirect()->back()->with('toast', [
                'message' => 'Absensi berhasil diambil.',
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
    public function terima($id_absensi)
    {
        $absensi = Absensi::findOrFail($id_absensi);
        $absensi->status = 'izin (diterima)';
        $absensi->save();

        return redirect()->back()->with('toast', [
            'message' => 'Izin telah diterima.',
            'type' => 'success',
        ]);
    }

    public function tolak($id_absensi)
    {
        $absensi = Absensi::findOrFail($id_absensi);
        $absensi->status = 'izin (ditolak)';
        $absensi->save();

        return redirect()->back()->with('toast', [
            'message' => 'Izin telah ditolak.',
            'type' => 'error',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_absensi)
    {
        $absensi = Absensi::findOrFail($id_absensi);

        if ($absensi->foto) {
            $filePath = 'public/absensi/' . $absensi->foto;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        }

        $absensi->delete();

        return redirect()->back()->with('toast', [
            'message' => 'Absensi berhasil dihapus!',
            'type' => 'success'
        ]);
    }
}
