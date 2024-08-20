<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Jurusan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function choose()
    {
        return view('auth.choose');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function reg_dosen()
    {
        return view('auth.reg-dosen');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function reg_mahasiswa()
    {
        return view('auth.reg-mahasiswa', [
            'jurusan' => Jurusan::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_mahasiswa(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'nim' => 'required|numeric|unique:mahasiswa',
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required|unique:mahasiswa',
                'jurusan' => 'required',
                'jenis_kelamin' => 'required',
                'password' => 'required|string|min:8',
            ], [
                'nim.unique' => 'NIM sudah digunakan, silakan gunakan NIM lain.',
                'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
                'email.unique' => 'Email sudah digunakan, silakan gunakan email lain.',
                'no_hp.unique' => 'No Handphone sudah digunakan, silakan gunakan no handphone lain.',
                'password.min' => 'Password minimal 8 Karakter.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::create([
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'mahasiswa',
            ]);

            if (!$user) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('toast', ['message' => 'Gagal mendaftar sebagai mahasiswa!', 'type' => 'error']);
            }

            $userID = $user->id_user;

            $mahasiswa = Mahasiswa::create([
                'id_user' => $userID,
                'id_jurusan' => $request->jurusan,
                'nim' => $request->nim,
                'name' => $request->nama,
                'username' => $request->username,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'jenis_kelamin' => $request->jenis_kelamin,
                'email' => $request->email,
            ]);

            if (!$mahasiswa) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('toast', ['message' => 'Gagal mendaftar sebagai mahasiswa!', 'type' => 'error']);
            }

            DB::commit();

            return redirect()->route('login')->with('toast', ['message' => 'Pendaftaran mahasiswa berhasil!', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast', ['message' => 'Terjadi kesalahan. Silakan coba lagi nanti.', 'type' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function store_dosen(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'nip' => 'required|numeric|unique:dosen',
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ], [
                'nip.unique' => 'NIP sudah digunakan, silakan gunakan NIP lain.',
                'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
                'email.unique' => 'Email sudah digunakan, silakan gunakan email lain.',
                'password.min' => 'Password minimal 8 Karakter.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::create([
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role ?? 'dosen',
            ]);

            if (!$user) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('toast', ['message' => 'Gagal mendaftar sebagai dosen!', 'type' => 'error']);
            }

            $userID = $user->id_user;

            $dosen = Dosen::create([
                'id_user' => $userID,
                'nip' => $request->nip,
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
            ]);

            if (!$dosen) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('toast', ['message' => 'Gagal mendaftar sebagai dosen!', 'type' => 'error']);
            }

            DB::commit();

            return redirect()->route('login')->with('toast', ['message' => 'Pendaftaran dosen berhasil!', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast', ['message' => 'Terjadi kesalahan. Silakan coba lagi nanti.', 'type' => 'error']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email salah.',
            'password.required' => 'Password harus diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $userRole = $user->role;

            $loginTime = Carbon::now();
            $request->session()->put([
                'login_time' => $loginTime->toDateTimeString(),
                'name' => $user->name,
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at
            ]);


            if ($userRole === 'dosen' || $userRole === 'mahasiswa' || $userRole === 'admin') {
                return redirect()->intended('dashboard')->with('toast', [
                    'message' => 'Login berhasil!',
                    'type' => 'success'
                ]);
            }

            return back()->with('toast', [
                'message' => 'Login gagal, role pengguna tidak dikenali.',
                'type' => 'error'
            ]);
        }

        return back()->withErrors([
            'loginError' => 'Email atau password salah.',
        ])->with('toast', [
            'message' => 'Email atau password salah.',
            'type' => 'error'
        ]);
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
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('toast', [
            'message' => 'Logout sukses, Anda telah keluar.',
            'type' => 'success'
        ]);
    }
}
