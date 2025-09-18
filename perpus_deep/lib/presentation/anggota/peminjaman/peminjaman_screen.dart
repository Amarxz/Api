import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
// import 'package:shared_preferences/shared_preferences.dart'; // Dihapus karena tidak digunakan langsung di sini
import 'package:intl/intl.dart'; // Import untuk DateFormat
import '../../../data/models/peminjaman_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart
// import 'peminjaman_edit_screen.dart'; // Dihapus karena PeminjamanEditScreen tidak lagi diakses dari sini
import 'package:provider/provider.dart'; // Import Provider
import '../../../data/providers/auth_provider.dart'; // Import AuthProvider
import 'peminjaman_create_screen.dart'; // Pastikan path ini benar jika Anda mengimpor layar AjukanPeminjamanScreen

class PeminjamanScreen extends StatefulWidget {
  const PeminjamanScreen({super.key});

  @override
  _PeminjamanScreenState createState() => _PeminjamanScreenState();
}

class _PeminjamanScreenState extends State<PeminjamanScreen> {
  List<Peminjaman> _peminjamans = [];
  bool _isLoading = true;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchPeminjaman();
  }

  // Fungsi _getAuthToken ini tidak lagi diperlukan karena token sudah diambil dari AuthProvider.
  /*
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    print('Auth Token retrieved from SharedPreferences in PeminjamanScreen: $token');
    return token;
  }
  */

  Future<void> _fetchPeminjaman() async {
    setState(() {
      _isLoading = true;
    });

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final token = authProvider.token;

    if (token == null) {
      setState(() => _isLoading = false);
      showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
      return;
    }

    try {
      print('Fetching peminjamans from: $_baseUrl/peminjamans');
      print('Headers being sent: {Authorization: Bearer $token}');

      final response = await http.get(
        Uri.parse('$_baseUrl/peminjamans'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
      );

      print('Response status for peminjamans: ${response.statusCode}');
      print('Response body for peminjamans: ${response.body}');

      if (response.statusCode == 200) {
        final dynamic decodedResponse = json.decode(response.body);

        List<dynamic> data;
        if (decodedResponse is List) {
          data = decodedResponse;
          print('API responded with a direct list for peminjamans.');
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data = decodedResponse['data'];
          print(
            'API responded with a map containing a "data" key for peminjamans.',
          );
        } else {
          showSnackBar('Format data peminjaman tidak sesuai.');
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
        showSnackBar('Data peminjaman berhasil dimuat.');
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat peminjaman. Silakan login ulang.',
        );
        setState(() => _isLoading = false);
      } else {
        showSnackBar('Gagal memuat data peminjaman: ${response.statusCode}');
        print(
          'Error: API returned status code ${response.statusCode} for peminjamans.',
        );
        setState(() => _isLoading = false);
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan: $error');
      print('Error during _fetchPeminjaman: $error');
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Peminjaman Saya')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _peminjamans.isEmpty
          ? const Center(child: Text('Tidak ada riwayat peminjaman.'))
          : RefreshIndicator(
              onRefresh: _fetchPeminjaman,
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
                          Text(
                            // Menampilkan judul buku
                            'Judul Buku: ${peminjaman.buku?.judul ?? 'N/A'}',
                            style: const TextStyle(fontWeight: FontWeight.bold),
                          ),
                          const SizedBox(height: 4.0),
                          // Menampilkan penulis buku
                          Text('Penulis: ${peminjaman.buku?.penulis ?? 'N/A'}'),
                          const SizedBox(height: 4.0),
                          Text(
                            'Tanggal Pinjam: ${DateFormat('yyyy-MM-dd').format(peminjaman.tanggalPinjam)}',
                          ),
                          Text(
                            'Tanggal Kembali: ${peminjaman.tanggalKembali != null ? DateFormat('yyyy-MM-dd').format(peminjaman.tanggalKembali!) : 'Belum Kembali'}',
                          ),
                          // PERBAIKAN: Hapus tombol Edit Tanggal Kembali
                          // ElevatedButton(
                          //   onPressed: () async {
                          //     final result = await Navigator.push(
                          //       context,
                          //       MaterialPageRoute(
                          //         builder: (context) => PeminjamanEditScreen(
                          //           peminjaman: peminjaman,
                          //         ),
                          //       ),
                          //     );
                          //     if (result == true) {
                          //       _fetchPeminjaman();
                          //     }
                          //   },
                          //   child: const Text(
                          //     'Edit Tanggal Kembali',
                          //   ),
                          // ),
                        ],
                      ),
                    ),
                  );
                },
              ),
            ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          final result = await Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => const PeminjamanCreateScreen(),
            ),
          );
          if (result == true) {
            _fetchPeminjaman();
          }
        },
        child: const Icon(Icons.add),
      ),
    );
  }
}
