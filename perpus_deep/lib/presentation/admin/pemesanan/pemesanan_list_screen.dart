import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart'; // Import shared_preferences
import '../../../data/models/pemesanan_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart (kini digunakan)

class PemesananListScreen extends StatefulWidget {
  const PemesananListScreen({super.key});

  @override
  _PemesananListScreenState createState() => _PemesananListScreenState();
}

class _PemesananListScreenState extends State<PemesananListScreen> {
  List<Pemesanan> _pemesanans = [];
  bool _isLoading = true;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchPemesanans();
  }

  // Hapus _showSnackBar lokal, karena kita akan menggunakan yang dari utils.dart
  // void _showSnackBar(String message) {
  //   if (mounted) {
  //     ScaffoldMessenger.of(context).showSnackBar(
  //       SnackBar(
  //         content: Text(message),
  //         duration: const Duration(seconds: 3),
  //         behavior: SnackBarBehavior.floating,
  //       ),
  //     );
  //   }
  // }

  // Fungsi untuk mendapatkan token dari SharedPreferences
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    // Menggunakan kunci 'token' yang konsisten dengan AuthProvider
    final token = prefs.getString('token');
    print(
      'Auth Token retrieved from SharedPreferences in PemesananListScreen: $token',
    ); // Debug print
    return token;
  }

  Future<void> _fetchPemesanans() async {
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
      print('Fetching pemesanans from: $_baseUrl/pemesanans');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
      );

      final response = await http.get(
        Uri.parse('$_baseUrl/pemesanans'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization':
              'Bearer $authToken', // Tambahkan header Authorization
        },
      );

      // Debug print: Respons dari API
      print('Response status for pemesanans: ${response.statusCode}');
      print('Response body for pemesanans: ${response.body}');

      if (response.statusCode == 200) {
        // Tangani respons API yang bisa berupa List<dynamic> langsung atau Map<String, dynamic> dengan kunci 'data'
        final dynamic decodedResponse = json.decode(response.body);

        List<dynamic> data;
        if (decodedResponse is List) {
          data = decodedResponse; // API mengembalikan list langsung
          print('API responded with a direct list for pemesanans.');
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data =
              decodedResponse['data']; // API mengembalikan map dengan kunci 'data'
          print(
            'API responded with a map containing a "data" key for pemesanans.',
          );
        } else {
          showSnackBar(
            'Format data pemesanan tidak sesuai.',
          ); // Menggunakan showSnackBar global
          setState(() => _isLoading = false);
          print(
            'Error: Unexpected API response format for pemesanans. Response was not a List or a Map with a "data" key.',
          );
          return;
        }

        setState(() {
          _pemesanans = data.map((json) => Pemesanan.fromJson(json)).toList();
          _isLoading = false;
        });
        showSnackBar(
          'Data pemesanan berhasil dimuat.',
        ); // Konfirmasi berhasil, menggunakan showSnackBar global
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat pemesanan. Silakan login ulang.',
        ); // Menggunakan showSnackBar global
        setState(() => _isLoading = false);
        // Opsi: Hapus token dan arahkan ke halaman login
        // final prefs = await SharedPreferences.getInstance();
        // await prefs.remove('token');
        // Navigator.pushReplacementNamed(context, '/login');
      } else {
        showSnackBar(
          'Gagal memuat data pemesanan: ${response.statusCode}',
        ); // Menggunakan showSnackBar global
        print(
          'Error: API returned status code ${response.statusCode} for pemesanans.',
        );
        setState(() => _isLoading = false);
      }
    } catch (error) {
      showSnackBar(
        'Terjadi kesalahan saat memuat pemesanan: $error',
      ); // Menggunakan showSnackBar global
      print('Error during _fetchPemesanans: $error'); // Debug print error
      setState(() => _isLoading = false);
    }
  }

  // Anda bisa menambahkan fungsi _deletePemesanan di sini jika diperlukan,
  // dengan menyertakan header Authorization juga.
  // Contoh:
  /*
  Future<void> _deletePemesanan(int id) async {
    try {
      final String? authToken = await _getAuthToken();
      if (authToken == null) {
        showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
        return;
      }
      final response = await http.delete(
        Uri.parse('$_baseUrl/pemesanans/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
        },
      );
      if (response.statusCode == 200) {
        showSnackBar('Pemesanan berhasil dihapus.');
        _fetchPemesanans();
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar('Autentikasi gagal. Silakan login ulang.');
      } else {
        showSnackBar('Gagal menghapus pemesanan: ${response.statusCode}');
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat menghapus pemesanan: $error');
    }
  }
  */

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Kelola Pemesanan')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _pemesanans.isEmpty
          ? const Center(child: Text('Tidak ada pemesanan.'))
          : RefreshIndicator(
              // Menambahkan RefreshIndicator
              onRefresh: _fetchPemesanans,
              child: ListView.builder(
                itemCount: _pemesanans.length,
                itemBuilder: (context, index) {
                  final pemesanan =
                      _pemesanans[index]; // Variabel yang benar adalah 'pemesanan'
                  return Card(
                    margin: const EdgeInsets.all(8.0),
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'ID: ${pemesanan.id}',
                            style: const TextStyle(fontWeight: FontWeight.bold),
                          ),
                          const SizedBox(height: 4.0),
                          Text('User ID: ${pemesanan.userId}'),
                          Text('Buku ID: ${pemesanan.bukuId}'),
                          Text(
                            'Tanggal Pesan: ${pemesanan.tanggalPesan}',
                          ), // Menggunakan 'pemesanan'
                          // Catatan: Model Pemesanan Anda saat ini tidak memiliki tanggalKembali,
                          // jadi baris ini akan error jika Anda belum menambahkannya ke model Pemesanan.
                          // Jika PemesananListScreen ini seharusnya menampilkan tanggalKembali,
                          // pastikan model Pemesanan memiliki field tersebut.
                          // Text('Tanggal Kembali: ${pemesanan.tanggalKembali ?? 'Belum Kembali'}'),
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
