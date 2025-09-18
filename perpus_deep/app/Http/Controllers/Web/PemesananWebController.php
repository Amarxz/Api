<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // Import DB facade untuk transaksi

class PemesananWebController extends Controller
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
        $query = Pemesanan::with(['user', 'buku'])->orderBy('id', 'asc');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('nama', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('buku', function ($bukuQuery) use ($search) {
                        $bukuQuery->where('judul', 'like', '%' . $search . '%');
                    });
            });
        }

        $pemesanans = $query->paginate(10);

        return view('pemesanan.index', compact('pemesanans'));
    }

    /**
     * Tampilkan formulir untuk membuat sumber daya baru.
     */
    public function create()
    {
        $users = User::where('role', 'anggota')->get();
        $bukus = Buku::all();
        return view('pemesanan.create', compact('users', 'bukus'));
    }

    /**
     * Simpan sumber daya yang baru dibuat ke penyimpanan.
     */
    public function store(Request $request)
    {
        // Gunakan transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'buku_id' => 'required|exists:bukus,id',
                'tanggal_pesan' => 'required|date',
            ]);

            $buku = Buku::lockForUpdate()->find($validated['buku_id']); // Lock buku untuk update

            // Periksa stok buku sebelum pemesanan
            if (!$buku || $buku->stok_saat_ini <= 0) {
                throw new \Exception('Stok buku tidak tersedia atau buku tidak ditemukan.');
            }

            // Kurangi stok saat ini
            $buku->decrement('stok_saat_ini');

            // Buat pemesanan
            Pemesanan::create($validated);

            DB::commit(); // Commit transaksi jika semua berhasil
            return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil ditambahkan dan stok buku diperbarui!');
        } catch (ValidationException $e) {
            DB::rollBack(); // Rollback jika validasi gagal
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada kesalahan lain
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan sumber daya yang ditentukan.
     */
    public function show(Pemesanan $pemesanan)
    {
        return view('pemesanan.show', compact('pemesanan'));
    }

    /**
     * Tampilkan formulir untuk mengedit sumber daya yang ditentukan.
     */
    public function edit(Pemesanan $pemesanan)
    {
        $users = User::where('role', 'anggota')->get();
        $bukus = Buku::all();
        return view('pemesanan.edit', compact('pemesanan', 'users', 'bukus'));
    }

    /**
     * Perbarui sumber daya yang ditentukan dalam penyimpanan.
     */
    public function update(Request $request, Pemesanan $pemesanan)
    {
        DB::beginTransaction(); // Mulai transaksi
        $originalBukuId = $pemesanan->buku_id; // Simpan buku_id asli

        try {
            $validated = $request->validate([
                'user_id' => 'sometimes|required|exists:users,id',
                'buku_id' => 'sometimes|required|exists:bukus,id',
                'tanggal_pesan' => 'sometimes|required|date',
            ]);

            // Jika buku_id diubah, sesuaikan stok buku lama dan buku baru
            if (isset($validated['buku_id']) && $validated['buku_id'] != $originalBukuId) {
                // Tambah stok buku lama
                $bukuLama = Buku::lockForUpdate()->find($originalBukuId);
                if ($bukuLama) {
                    $bukuLama->increment('stok_saat_ini');
                }

                // Kurangi stok buku baru
                $bukuBaru = Buku::lockForUpdate()->find($validated['buku_id']);
                if (!$bukuBaru || $bukuBaru->stok_saat_ini <= 0) {
                    throw new \Exception('Stok buku yang dipilih tidak cukup atau buku tidak ditemukan.');
                }
                $bukuBaru->decrement('stok_saat_ini');
            }

            $pemesanan->update($validated);

            DB::commit(); // Commit transaksi
            return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil diperbarui dan stok buku disesuaikan!');
        } catch (ValidationException $e) {
            DB::rollBack(); // Rollback jika validasi gagal
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada kesalahan lain
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus sumber daya yang ditentukan dari penyimpanan.
     */
    public function destroy(Pemesanan $pemesanan)
    {
        DB::beginTransaction(); // Mulai transaksi
        try {
            // Tambahkan stok buku kembali saat pemesanan dihapus
            $buku = Buku::lockForUpdate()->find($pemesanan->buku_id);
            if ($buku) {
                $buku->increment('stok_saat_ini');
            }

            $pemesanan->delete();

            DB::commit(); // Commit transaksi
            return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil dihapus dan stok buku dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada kesalahan
            return redirect()->route('pemesanan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
