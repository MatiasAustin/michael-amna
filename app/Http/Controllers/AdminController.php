<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function store(Request $request)
    {
        if (Admin::count() >= 1) {
            return back()->with('error', 'Admin sudah ada. Tidak bisa tambah lagi.');
        }

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'Admin berhasil dibuat.');
    }

}
