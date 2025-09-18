<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi; // Menggunakan model Notifikasi Anda yang sudah ada
use App\Models\User;     // Menggunakan model User Anda yang sudah ada
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotifikasiWebController extends Controller
{
    /**
     * Tampilkan daftar sumber daya.
     */

    public function __construct()
    {
        // Hanya admin yang bisa mengakses semua metode di controller ini,
        // kecuali mungkin 'show' jika diizinkan untuk user itu sendiri.
        // Untuk CRUD admin, kita asumsikan semua diakses admin.
        $this->middleware('admin'); // Kita akan membuat middleware 'admin' jika belum ada
    }

    public function index(Request $request) // Menerima Request untuk pencarian
    {
        // Mulai dengan query dasar, eager load relasi user, dan urutkan secara ascending
        $query = Notifikasi::with(['user'])->orderBy('id', 'asc');

        // Jika ada input 'search' dari form
        if ($request->has('search')) {
            $search = $request->input('search');
            // Menambahkan kondisi WHERE untuk pencarian berdasarkan pesan atau nama user
            $query->where(function ($q) use ($search) {
                // Cari berdasarkan pesan notifikasi
                $q->where('pesan', 'like', '%' . $search . '%');

                // Cari berdasarkan nama user (relasi 'user' pada model Notifikasi)
                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('nama', 'like', '%' . $search . '%'); // Menggunakan 'nama' sesuai model User
                });
            });
        }

        // Terapkan paginasi pada query yang sudah difilter
        $notifikasis = $query->paginate(10);

        return view('notifikasis.index', compact('notifikasis'));
    }

    /**
     * Tampilkan formulir untuk membuat sumber daya baru.
     */
    public function create()
    {
        // Ambil semua user (bisa juga difilter berdasarkan role jika notifikasi hanya untuk anggota)
        $users = User::all();
        return view('notifikasis.create', compact('users'));
    }

    /**
     * Simpan sumber daya yang baru dibuat ke penyimpanan.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'pesan' => 'required|string',
            ]);

            // Set 'dibaca' default ke false
            $validated['dibaca'] = false;

            Notifikasi::create($validated);

            return redirect()->route('notifikasis.index')->with('success', 'Notifikasi berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan sumber daya yang ditentukan.
     */
    public function show(Notifikasi $notifikasi)
    {
        return view('notifikasis.show', compact('notifikasi'));
    }

    /**
     * Tampilkan formulir untuk mengedit sumber daya yang ditentukan.
     */
    public function edit(Notifikasi $notifikasi)
    {
        $users = User::all();
        return view('notifikasis.edit', compact('notifikasi', 'users'));
    }

    /**
     * Perbarui sumber daya yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, Notifikasi $notifikasi)
    {
        try {
            // Validasi untuk update. Asumsi user_id dan pesan tidak selalu diubah,
            // tapi 'dibaca' bisa diubah.
            $validated = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id',
                'pesan' => 'sometimes|required|string',
                'dibaca' => 'sometimes|boolean', // Untuk menandai sudah dibaca atau belum
            ]);

            $notifikasi->update($validated);

            return redirect()->route('notifikasis.index')->with('success', 'Notifikasi berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function sendToAll(Request $request)
    {
        $validated = $request->validate([
            'pesan' => 'required|string', // Hanya pesan yang diperlukan
        ]);

        // Ambil semua user yang ber-role 'anggota' (atau semua user tergantung kebutuhan)
        $users = \App\Models\User::where('role', 'anggota')->get(); // Atau User::all(); jika ingin semua user

        foreach ($users as $user) {
            \App\Models\Notifikasi::create([
                'user_id' => $user->id,
                'pesan' => $validated['pesan'],
                'dibaca' => false,
            ]);
        }

        return redirect()->route('notifikasis.index')->with('success', 'Notifikasi berhasil dikirim ke semua anggota!');
    }

    /**
     * Hapus sumber daya yang ditentukan dari penyimpanan.
     */
    public function destroy(Notifikasi $notifikasi)
    {
        try {
            $notifikasi->delete();
            return redirect()->route('notifikasis.index')->with('success', 'Notifikasi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('notifikasis.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
