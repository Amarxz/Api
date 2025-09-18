class User {
  final int id;
  final String nama;
  final String email;
  final String role;
  String?
  telepon; // Telepon diubah menjadi non-final agar bisa diupdate jika dibutuhkan

  User({
    required this.id,
    required this.nama,
    required this.email,
    required this.role,
    this.telepon, // Tambahkan telepon di konstruktor
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      nama: json['nama'],
      email: json['email'],
      role: json['role'],
      telepon: json['telepon'], // Parse telepon dari JSON
    );
  }

  // PERBAIKAN: Tambahkan metode toJson()
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama': nama,
      'email': email,
      'role': role,
      'telepon': telepon,
      // Penting: Jangan sertakan password di sini untuk keamanan
    };
  }
}
