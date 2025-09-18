<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        return Notifikasi::with('user')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pesan' => 'required|string',
        ]);

        return Notifikasi::create([
            'user_id' => $request->user_id,
            'pesan' => $request->pesan,
            'dibaca' => false,
        ]);
    }

    public function show($id)
    {
        return Notifikasi::with('user')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $notifikasi = Notifikasi::findOrFail($id);
        $notifikasi->update([
            'dibaca' => $request->boolean('dibaca')
        ]);
        return $notifikasi;
    }

    public function destroy($id)
    {
        return Notifikasi::destroy($id);
    }
}
