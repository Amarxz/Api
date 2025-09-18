import 'buku_model.dart'; // Sesuaikan path jika berbeda
import 'user_model.dart'; // Sesuaikan path jika berbeda

class Pemesanan {
  final int id;
  final int userId;
  final int bukuId;
  final DateTime tanggalPesan;

  final User? user; // PERBAIKAN: Tambahkan properti untuk relasi user
  final Buku? buku; // PERBAIKAN: Tambahkan properti untuk relasi buku

  Pemesanan({
    required this.id,
    required this.userId,
    required this.bukuId,
    required this.tanggalPesan,
    this.user, // Tambahkan di konstruktor
    this.buku, // Tambahkan di konstruktor
  });

  factory Pemesanan.fromJson(Map<String, dynamic> json) {
    return Pemesanan(
      id: json['id'],
      userId: json['user_id'],
      bukuId: json['buku_id'],
      tanggalPesan: DateTime.parse(json['tanggal_pesan']),
      user: json['user'] != null
          ? User.fromJson(json['user'])
          : null, // PERBAIKAN: Uraikan objek user
      buku: json['buku'] != null
          ? Buku.fromJson(json['buku'])
          : null, // PERBAIKAN: Uraikan objek buku
    );
  }
}
