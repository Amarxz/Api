// Import model User jika belum
import 'user_model.dart'; // Sesuaikan path jika berbeda

class Notifikasi {
  final int id;
  final int userId;
  final String pesan;
  final bool dibaca;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  final User? user; // PERBAIKAN: Tambahkan properti untuk relasi user

  Notifikasi({
    required this.id,
    required this.userId,
    required this.pesan,
    required this.dibaca,
    this.createdAt,
    this.updatedAt,
    this.user, // PERBAIKAN: Tambahkan di constructor
  });

  factory Notifikasi.fromJson(Map<String, dynamic> json) {
    return Notifikasi(
      id: json['id'],
      userId: json['user_id'],
      pesan: json['pesan'],
      dibaca: json['dibaca'] == 1 || json['dibaca'] == true,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
      user: json['user'] != null
          ? User.fromJson(json['user'])
          : null, // PERBAIKAN: Uraikan objek user
    );
  }
}
