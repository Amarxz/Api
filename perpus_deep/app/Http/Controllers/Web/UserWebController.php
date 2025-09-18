<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User; // Menggunakan model User Anda yang sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Untuk hashing password
use Illuminate\Validation\ValidationException;

class UserWebController extends Controller
{
    /**
     * Middleware untuk otorisasi akses.
     * Hanya admin yang bisa mengakses sebagian besar fungsi ini.
     */
    public function __construct()
    {
        // Hanya admin yang bisa mengakses semua metode di controller ini,
        // kecuali mungkin 'show' jika diizinkan untuk user itu sendiri.
        // Untuk CRUD admin, kita asumsikan semua diakses admin.
        $this->middleware('admin'); // Kita akan membuat middleware 'admin' jika belum ada
    }

    /**
     * Tampilkan daftar sumber daya.
     */
    public function index(Request $request) // Menerima Request untuk pencarian
    {
        // Mulai dengan query dasar dan urutkan secara ascending
        $query = User::orderBy('id', 'asc');

        // Jika ada input 'search' dari form
        if ($request->has('search')) {
            $search = $request->input('search');
            // Menambahkan kondisi WHERE untuk pencarian berdasarkan nama, email, atau role
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%') // Menggunakan 'nama' sesuai model User
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        // Terapkan paginasi pada query yang sudah difilter
        $users = $query->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Tampilkan formulir untuk membuat sumber daya baru.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Simpan sumber daya yang baru dibuat ke penyimpanan.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'telepon' => 'nullable|string|max:15',
                'role' => 'required|in:admin,anggota', // Role wajib dipilih oleh admin
            ]);

            // Hash password sebelum menyimpan
            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);

            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan sumber daya yang ditentukan.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Tampilkan formulir untuk mengedit sumber daya yang ditentukan.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Perbarui sumber daya yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'telepon' => 'nullable|string|max:15',
                'role' => 'required|in:admin,anggota', // Role wajib dipilih oleh admin
            ]);

            // Hanya update password jika ada password baru yang dikirim
            if ($request->filled('password')) {
                $request->validate(['password' => 'string|min:6']);
                $validated['password'] = Hash::make($request->password);
            } else {
                // Jika password tidak dikirim, hapus dari $validated agar tidak menimpa password yang ada
                unset($validated['password']);
            }

            $user->update($validated);

            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus sumber daya yang ditentukan dari penyimpanan.
     */
    public function destroy(User $user)
    {
        try {
            // Opsional: Pastikan admin tidak bisa menghapus dirinya sendiri
            if (auth()->id() === $user->id) {
                return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            }
            // Opsional: Hapus semua data terkait user (peminjaman, pemesanan, notifikasi)
            // sebelum menghapus user, atau set foreign key menjadi NULL/CASCADE DELETE di migrasi.
            // Jika ada relasi cascade delete di migrasi, ini akan otomatis.

            $user->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
