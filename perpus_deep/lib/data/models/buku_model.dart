class Buku {
  final int id;
  final String judul;
  final String penulis;
  final int kategoriId;
  final int stokTotal;
  final int stokSaatIni;
  final String? cover; // Tambahkan field cover (nullable)
  final String? publisher; // Tambahkan field publisher (nullable)
  final String?
  tanggalPublikasi; // Tambahkan field tanggalPublikasi (nullable, akan jadi string dari JSON)

  Buku({
    required this.id,
    required this.judul,
    required this.penulis,
    required this.kategoriId,
    required this.stokTotal,
    required this.stokSaatIni,
    this.cover, // Tambahkan di constructor
    this.publisher, // Tambahkan di constructor
    this.tanggalPublikasi, // Tambahkan di constructor
  });

  factory Buku.fromJson(Map<String, dynamic> json) {
    return Buku(
      id: json['id'],
      judul: json['judul'],
      penulis: json['penulis'],
      kategoriId: json['kategori_id'],
      stokTotal: json['stok_total'],
      stokSaatIni: json['stok_saat_ini'],
      cover: json['cover'], // Parsing cover dari JSON
      publisher: json['publisher'], // Parsing publisher dari JSON
      tanggalPublikasi:
          json['tanggal_publikasi'], // Parsing tanggal_publikasi dari JSON
    );
  }

  // Anda bisa menambahkan metode toJson jika ingin mengirim data dari Dart ke API
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'judul': judul,
      'penulis': penulis,
      'kategori_id': kategoriId,
      'stok_total': stokTotal,
      'stok_saat_ini': stokSaatIni,
      'cover': cover,
      'publisher': publisher,
      'tanggal_publikasi': tanggalPublikasi,
    };
  }
}
