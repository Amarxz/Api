// Import model User dan Buku jika belum
import 'user_model.dart'; // Sesuaikan path jika berbeda
import 'buku_model.dart'; // Sesuaikan path jika berbeda

class Peminjaman {
  final int id;
  final int userId;
  final int bukuId;
  final DateTime tanggalPinjam;
  final DateTime? tanggalKembali; // <-- Diubah menjadi nullable (ada tanda tanya ?)

  final User? user; // Ini sudah ada dari update sebelumnya
  final Buku? buku; // PERBAIKAN: Tambahkan properti untuk relasi buku

  Peminjaman({
    required this.id,
    required this.userId,
    required this.bukuId,
    required this.tanggalPinjam,
    this.tanggalKembali, // <-- Sekarang tidak wajib diisi karena nullable
    this.user, // Sudah ada
    this.buku, // PERBAIKAN: Tambahkan di constructor
  });

  factory Peminjaman.fromJson(Map<String, dynamic> json) {
    return Peminjaman(
      id: json['id'],
      userId: json['user_id'],
      bukuId: json['buku_id'],
      tanggalPinjam: DateTime.parse(json['tanggal_pinjam']),
      tanggalKembali: json['tanggal_kembali'] != null 
          ? DateTime.parse(json['tanggal_kembali']) 
          : null,
      user: json['user'] != null ? User.fromJson(json['user']) : null, // Sudah ada
      buku: json['buku'] != null ? Buku.fromJson(json['buku']) : null, // PERBAIKAN: Uraikan objek buku
    );
  }
}
