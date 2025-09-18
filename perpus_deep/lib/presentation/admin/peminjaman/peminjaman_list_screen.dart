import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
// import 'package:shared_preferences/shared_preferences.dart'; // Dihapus karena tidak digunakan langsung di sini
import 'package:intl/intl.dart'; // Import untuk DateFormat
import '../../../data/models/peminjaman_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart
import 'peminjaman_edit_screen.dart'; // Untuk input tanggal kembali
import 'package:provider/provider.dart'; // Import Provider
import '../../../data/providers/auth_provider.dart'; // Import AuthProvider

class PeminjamanListScreen extends StatefulWidget {
  const PeminjamanListScreen({super.key});

  @override
  _PeminjamanListScreenState createState() => _PeminjamanListScreenState();
}

class _PeminjamanListScreenState extends State<PeminjamanListScreen> {
  List<Peminjaman> _peminjamans = [];
  bool _isLoading = true;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchPeminjamans();
  }

  Future<void> _fetchPeminjamans() async {
    setState(() {
      _isLoading = true;
    });
    try {
      // Mengambil token dari AuthProvider (yang mengelola shared_preferences)
      // Menggunakan Provider.of<AuthProvider> dengan tipe eksplisit
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final String? authToken =
          authProvider.token; // Ambil token dari AuthProvider

      if (authToken == null) {
        showSnackBar(
          'Anda belum login. Silakan login terlebih dahulu.',
        ); // Menggunakan showSnackBar dari utils.dart
        setState(() => _isLoading = false);
        return;
      }

      // Debug print: URL dan Header yang dikirim
      print('Fetching peminjamans from: $_baseUrl/peminjamans');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
      );

      final response = await http.get(
        Uri.parse('$_baseUrl/peminjamans'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization':
              'Bearer $authToken', // Tambahkan header Authorization
        },
      );

      // Debug print: Respons dari API
      print('Response status for peminjamans: ${response.statusCode}');
      print('Response body for peminjamans: ${response.body}');

      if (response.statusCode == 200) {
        // Tangani respons API yang bisa berupa List<dynamic> langsung atau Map<String, dynamic> dengan kunci 'data'
        final dynamic decodedResponse = json.decode(response.body);

        List<dynamic> data;
        if (decodedResponse is List) {
          data = decodedResponse; // API mengembalikan list langsung
          print('API responded with a direct list for peminjamans.');
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data =
              decodedResponse['data']; // API mengembalikan map dengan kunci 'data'
          print(
            'API responded with a map containing a "data" key for peminjamans.',
          );
        } else {
          showSnackBar(
            'Format data peminjaman tidak sesuai.',
          ); // Menggunakan showSnackBar dari utils.dart
          setState(() => _isLoading = false);
          print(
            'Error: Unexpected API response format for peminjamans. Response was not a List or a Map with a "data" key.',
          );
          return;
        }

        setState(() {
          _peminjamans = data.map((json) => Peminjaman.fromJson(json)).toList();
          _isLoading = false;
        });
        showSnackBar(
          'Data peminjaman berhasil dimuat.',
        ); // Konfirmasi berhasil, menggunakan showSnackBar dari utils.dart
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat peminjaman. Silakan login ulang.',
        ); // Menggunakan showSnackBar dari utils.dart
        setState(() => _isLoading = false);
        // Opsi: Hapus token dan arahkan ke halaman login
        // final prefs = await SharedPreferences.getInstance();
        // await prefs.remove('token');
        // Navigator.pushReplacementNamed(context, '/login');
      } else {
        showSnackBar(
          'Gagal memuat data peminjaman: ${response.statusCode}',
        ); // Menggunakan showSnackBar dari utils.dart
        print(
          'Error: API returned status code ${response.statusCode} for peminjamans.',
        );
        setState(() => _isLoading = false);
      }
    } catch (error) {
      showSnackBar(
        'Terjadi kesalahan saat memuat peminjaman: $error',
      ); // Menggunakan showSnackBar dari utils.dart
      print('Error during _fetchPeminjamans: $error'); // Debug print error
      setState(() => _isLoading = false);
    }
  }

  // Anda bisa menambahkan fungsi _deletePeminjaman di sini jika diperlukan,
  // dengan menyertakan header Authorization juga.
  // Contoh:
  /*
  Future<void> _deletePeminjaman(int id) async {
    try {
      final String? authToken = await _getAuthToken();
      if (authToken == null) {
        showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
        return;
      }
      final response = await http.delete(
        Uri.parse('$_baseUrl/peminjamans/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
        },
      );
      if (response.statusCode == 200) {
        showSnackBar('Peminjaman berhasil dihapus.');
        _fetchPeminjamans();
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar('Autentikasi gagal. Silakan login ulang.');
      } else {
        showSnackBar('Gagal menghapus peminjaman: ${response.statusCode}');
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat menghapus peminjaman: $error');
    }
  }
  */

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Kelola Peminjaman')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _peminjamans.isEmpty
          ? const Center(child: Text('Tidak ada peminjaman.'))
          : RefreshIndicator(
              // Menambahkan RefreshIndicator
              onRefresh: _fetchPeminjamans,
              child: ListView.builder(
                itemCount: _peminjamans.length,
                itemBuilder: (context, index) {
                  final peminjaman = _peminjamans[index];
                  return Card(
                    margin: const EdgeInsets.all(8.0),
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Menampilkan nama pengguna dan judul buku
                          Text(
                            'Peminjam: ${peminjaman.user?.nama ?? 'N/A'}', // Menggunakan properti 'user'
                            style: const TextStyle(fontWeight: FontWeight.bold),
                          ),
                          Text(
                            'Buku: ${peminjaman.buku?.judul ?? 'N/A'}', // Menggunakan properti 'buku'
                            style: const TextStyle(fontStyle: FontStyle.italic),
                          ),
                          const SizedBox(height: 4.0),
                          Text(
                            'Tanggal Pinjam: ${DateFormat('yyyy-MM-dd').format(peminjaman.tanggalPinjam)}',
                          ),
                          // Kini `tanggalKembali` bisa null, jadi `??` operator valid
                          Text(
                            'Tanggal Kembali: ${peminjaman.tanggalKembali != null ? DateFormat('yyyy-MM-dd').format(peminjaman.tanggalKembali!) : 'Belum Kembali'}',
                          ),
                          const SizedBox(height: 8.0),
                          ElevatedButton(
                            onPressed: () async {
                              // Mengubah menjadi async
                              final result = await Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => PeminjamanEditScreen(
                                    peminjaman: peminjaman,
                                  ),
                                ),
                              );
                              // Refresh list setelah kembali dari edit screen
                              if (result == true) {
                                _fetchPeminjamans();
                              }
                            },
                            child: const Text(
                              'Edit Tanggal Kembali',
                            ), // Menambahkan const
                          ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),
    );
  }
}
