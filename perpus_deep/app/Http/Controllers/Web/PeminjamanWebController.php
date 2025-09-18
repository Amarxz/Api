<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PeminjamanWebController extends Controller
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

    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku'])->orderBy('id', 'asc');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nama', 'like', '%' . $search . '%'); // <-- UBAH DARI 'name' MENJADI 'nama'
                    })
                    ->orWhereHas('buku', function ($bukuQuery) use ($search) {
                        $bukuQuery->where('judul', 'like', '%' . $search . '%');
                    });
            });
        }

        $peminjamans = $query->paginate(10);

        return view('peminjaman.index', compact('peminjamans'));
    }

    /**
     * Tampilkan formulir untuk membuat sumber daya baru.
     */
    public function create()
    {
        $users = User::where('role', 'anggota')->get();
        $bukus = Buku::all();
        return view('peminjaman.create', compact('users', 'bukus'));
    }

    /**
     * Simpan sumber daya yang baru dibuat ke penyimpanan.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'buku_id' => 'required|exists:bukus,id',
                'tanggal_pinjam' => 'required|date',
                'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
                'status' => 'nullable|string|in:dipinjam,dikembalikan,terlambat,dibatalkan',
            ]);

            $validated['status'] = $validated['status'] ?? 'dipinjam';

            $buku = Buku::find($validated['buku_id']);
            if ($buku && $buku->stok_saat_ini > 0) {
                $buku->decrement('stok_saat_ini');
            } else {
                throw new \Exception('Stok buku tidak cukup atau buku tidak ditemukan.');
            }

            Peminjaman::create($validated);

            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil ditambahkan!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan sumber daya yang ditentukan.
     */
    public function show(Peminjaman $peminjaman)
    {
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Tampilkan formulir untuk mengedit sumber daya yang ditentukan.
     */
    public function edit(Peminjaman $peminjaman)
    {
        $users = User::where('role', 'anggota')->get();
        $bukus = Buku::all();
        return view('peminjaman.edit', compact('peminjaman', 'users', 'bukus'));
    }

    /**
     * Perbarui sumber daya yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $originalStatus = $peminjaman->status;
        $originalBukuId = $peminjaman->buku_id;

        try {
            $validated = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id',
                'buku_id' => 'sometimes|required|exists:bukus,id',
                'tanggal_pinjam' => 'sometimes|required|date',
                'tanggal_kembali' => 'sometimes|required|date|after_or_equal:tanggal_pinjam',
                'status' => 'sometimes|required|string|in:dipinjam,dikembalikan,terlambat,dibatalkan',
            ]);

            if (isset($validated['buku_id']) && $validated['buku_id'] != $originalBukuId) {
                $bukuLama = Buku::find($originalBukuId);
                if ($bukuLama) {
                    $bukuLama->increment('stok_saat_ini');
                }

                $bukuBaru = Buku::find($validated['buku_id']);
                if ($bukuBaru && $bukuBaru->stok_saat_ini > 0) {
                    $bukuBaru->decrement('stok_saat_ini');
                } else {
                    if ($bukuLama) $bukuLama->decrement('stok_saat_ini');
                    throw new \Exception('Stok buku yang dipilih tidak cukup atau buku tidak ditemukan.');
                }
            }

            if (isset($validated['status']) && $validated['status'] == 'dikembalikan' && $originalStatus != 'dikembalikan') {
                $buku = Buku::find($peminjaman->buku_id);
                if ($buku) {
                    $buku->increment('stok_saat_ini');
                }
            } else if (isset($validated['status']) && $validated['status'] != 'dikembalikan' && $originalStatus == 'dikembalikan') {
                $buku = Buku::find($peminjaman->buku_id);
                if ($buku && $buku->stok_saat_ini > 0) {
                    $buku->decrement('stok_saat_ini');
                } else {
                    throw new \Exception('Stok buku tidak cukup untuk mengubah status dari "dikembalikan".');
                }
            }

            $peminjaman->update($validated);

            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diperbarui!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus sumber daya yang ditentukan dari penyimpanan.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        try {
            if ($peminjaman->status == 'dipinjam') {
                $buku = Buku::find($peminjaman->buku_id);
                if ($buku) {
                    $buku->increment('stok_saat_ini');
                }
            }
            $peminjaman->delete();
            return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('peminjaman.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
