<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = $request->session()->get('role');
        $username = $request->session()->get('username');
        $login = $request->session()->get('login_time');
        $register = $request->session()->get('created_at');
        $email = $request->session()->get('email');
        $name = $request->session()->get('name');
        $id_user = $request->session()->get('id_user');

        return view('dashboard.profile', [
            'role' => $role,
            'username' => $username,
            'login' => $login,
            'register' => $register,
            'email' => $email,
            'name' => $name,
            'id_user' => $id_user,
        ]);
        return view('dashboard.profile');
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
    public function destroy(string $id)
    {
        //
    }
}
