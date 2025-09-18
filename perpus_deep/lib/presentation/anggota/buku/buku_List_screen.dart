import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart'; // Import shared_preferences
import 'package:intl/intl.dart'; // Import intl untuk format tanggal
import '../../../data/models/buku_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart

class BukuListScreen extends StatefulWidget {
  const BukuListScreen({super.key});

  @override
  _BukuListScreenState createState() => _BukuListScreenState();
}

class _BukuListScreenState extends State<BukuListScreen> {
  List<Buku> _bukus = [];
  bool _isLoading = true;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchBuku();
  }

  // Fungsi untuk mendapatkan token dari SharedPreferences
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    // Menggunakan kunci 'token' yang konsisten dengan AuthProvider
    final token = prefs.getString('token');
    print(
      'Auth Token retrieved from SharedPreferences in BukuListScreen: $token',
    ); // Debug print
    return token;
  }

  Future<void> _fetchBuku() async {
    setState(() {
      _isLoading = true;
    });
    try {
      final String? authToken = await _getAuthToken();
      if (authToken == null) {
        showSnackBar(
          'Anda belum login. Silakan login terlebih dahulu.',
        ); // Menggunakan showSnackBar global
        setState(() => _isLoading = false);
        return;
      }

      // Debug print: URL dan Header yang dikirim
      print('Fetching books from: $_baseUrl/bukus');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
      );

      final response = await http.get(
        Uri.parse('$_baseUrl/bukus'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization':
              'Bearer $authToken', // Tambahkan header Authorization
        },
      );

      // Debug print: Respons dari API
      print('Response status for books: ${response.statusCode}');
      print('Response body for books: ${response.body}');

      if (response.statusCode == 200) {
        // Tangani respons API yang bisa berupa List<dynamic> langsung atau Map<String, dynamic> dengan kunci 'data'
        final dynamic decodedResponse = json.decode(response.body);

        List<dynamic> data;
        if (decodedResponse is List) {
          data = decodedResponse; // API mengembalikan list langsung
          print('API responded with a direct list for books.');
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data =
              decodedResponse['data']; // API mengembalikan map dengan kunci 'data'
          print('API responded with a map containing a "data" key for books.');
        } else {
          showSnackBar(
            'Format data buku tidak sesuai.',
          ); // Menggunakan showSnackBar global
          setState(() => _isLoading = false);
          print(
            'Error: Unexpected API response format for books. Response was not a List or a Map with a "data" key.',
          );
          return;
        }

        setState(() {
          _bukus = data.map((json) => Buku.fromJson(json)).toList();
          _isLoading = false;
        });
        showSnackBar('Data buku berhasil dimuat.'); // Konfirmasi berhasil
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat buku. Silakan login ulang.',
        ); // Menggunakan showSnackBar global
        setState(() => _isLoading = false);
        // Opsi: Hapus token dan arahkan ke halaman login
        // final prefs = await SharedPreferences.getInstance();
        // await prefs.remove('token');
        // Navigator.pushReplacementNamed(context, '/login');
      } else {
        showSnackBar(
          'Gagal memuat data buku: ${response.statusCode}',
        ); // Menggunakan showSnackBar global
        print(
          'Error: API returned status code ${response.statusCode} for books.',
        );
        setState(() => _isLoading = false);
      }
    } catch (error) {
      showSnackBar(
        'Terjadi kesalahan saat memuat buku: $error',
      ); // Menggunakan showSnackBar global
      print('Error during _fetchBuku: $error'); // Debug print error
      setState(() => _isLoading = false);
    }
  }

  // Metode _deleteBuku dihapus karena fungsionalitas hapus tidak diperlukan di sini

  // Metode _showDeleteConfirmation dihapus karena fungsionalitas hapus tidak diperlukan di sini

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Daftar Buku'), // Judul diubah menjadi "Daftar Buku"
        // actions: [] dihapus karena tidak ada fungsionalitas tambah
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _bukus.isEmpty
          ? const Center(child: Text('Tidak ada buku.'))
          : RefreshIndicator(
              onRefresh: _fetchBuku,
              child: ListView.builder(
                itemCount: _bukus.length,
                itemBuilder: (context, index) {
                  final buku = _bukus[index];
                  // Format tanggal publikasi jika ada
                  String? formattedTanggalPublikasi;
                  if (buku.tanggalPublikasi != null) {
                    try {
                      // Gunakan operator '!' di sini karena kita sudah memeriksa '!= null'
                      final dateTime = DateTime.parse(buku.tanggalPublikasi!);
                      formattedTanggalPublikasi = DateFormat(
                        'dd MMMMyyyy',
                      ).format(dateTime); // Corrected format
                    } catch (e) {
                      print(
                        'Error parsing tanggalPublikasi: ${buku.tanggalPublikasi} - $e',
                      );
                      formattedTanggalPublikasi =
                          buku.tanggalPublikasi; // Fallback ke string asli
                    }
                  }

                  return Card(
                    margin: const EdgeInsets.all(8.0),
                    child: ListTile(
                      // Menampilkan gambar cover jika ada
                      leading: SizedBox(
                        width: 50, // Lebar fixed untuk gambar
                        height: 70, // Tinggi fixed untuk gambar
                        child: buku.cover != null && buku.cover!.isNotEmpty
                            ? Image.network(
                                // Pastikan URL base path untuk gambar di Laravel sudah benar
                                // Misalnya: 'http://10.0.2.2:8000/storage/'
                                '${_baseUrl.replaceAll('/api', '/storage')}/${buku.cover!}',
                                fit: BoxFit.cover,
                                errorBuilder: (context, error, stackTrace) {
                                  return const Icon(
                                    Icons.broken_image,
                                    size: 40,
                                  ); // Placeholder jika gambar gagal dimuat
                                },
                              )
                            : const Icon(
                                Icons.book_outlined,
                                size: 40,
                              ), // Placeholder jika tidak ada cover
                      ),
                      title: Text(
                        buku.judul,
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      subtitle: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Penulis: ${buku.penulis}'),

                          // Menampilkan nama kategori jika relasi kategori dimuat
                          // Saat ini, model Buku hanya memiliki kategoriId.
                          // Untuk menampilkan nama kategori, API perlu mengembalikan object kategori.
                          // Misalnya, 'kategori': {'id': 1, 'nama': 'Fiksi'}.
                          // Jika API Anda sudah mengembalikan demikian, Anda perlu update model Buku Dart
                          // untuk menyertakan Kategori object, lalu akses buku.kategori.nama.
                          if (buku.publisher != null &&
                              buku.publisher!.isNotEmpty)
                            Text('Penerbit: ${buku.publisher!}'),
                          if (formattedTanggalPublikasi != null &&
                              formattedTanggalPublikasi.isNotEmpty)
                            Text('Publikasi: $formattedTanggalPublikasi'),
                          Text('Stok: ${buku.stokSaatIni}/${buku.stokTotal}'),
                        ],
                      ),
                      // trailing dan onTap dihapus karena tidak ada fungsionalitas edit/hapus
                    ),
                  );
                },
              ),
            ),
    );
  }
}
