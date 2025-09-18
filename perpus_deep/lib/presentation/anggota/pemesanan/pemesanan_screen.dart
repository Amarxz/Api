import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
// import 'package:shared_preferences/shared_preferences.dart'; // Dihapus karena tidak digunakan langsung di sini
import 'package:intl/intl.dart'; // Import untuk DateFormat
import '../../../data/models/pemesanan_model.dart'; // Pastikan ini adalah model yang sudah diupdate
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart
import 'package:provider/provider.dart';
import '../../../data/providers/auth_provider.dart';
import 'pemesanan_create_screen.dart'; // Pastikan path ini benar jika Anda mengimpor layar PemesananCreateScreen

class PemesananScreen extends StatefulWidget {
  const PemesananScreen({super.key});

  @override
  _PemesananScreenState createState() => _PemesananScreenState();
}

class _PemesananScreenState extends State<PemesananScreen> {
  List<Pemesanan> _pemesanans = [];
  bool _isLoading = true;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchPemesanan();
  }

  // Fungsi _getAuthToken ini tidak lagi diperlukan karena token sudah diambil dari AuthProvider.
  /*
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    print('Auth Token retrieved from SharedPreferences in PemesananScreen: $token'); // Debug print
    return token;
  }
  */

  Future<void> _fetchPemesanan() async {
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
      print('Fetching pemesanans from: $_baseUrl/pemesanans');
      print('Headers being sent: {Authorization: Bearer $token}');

      final response = await http.get(
        Uri.parse('$_baseUrl/pemesanans'),
        headers: {
          'Content-Type':
              'application/json', // Opsional, tetapi baik untuk konsistensi
          'Authorization': 'Bearer $token',
          'Accept':
              'application/json', // Tambahkan header ini untuk memastikan respons JSON
        },
      );

      print('Response status for pemesanans: ${response.statusCode}');
      print('Response body for pemesanans: ${response.body}');

      if (response.statusCode == 200) {
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
          showSnackBar('Format data pemesanan tidak sesuai.');
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
        showSnackBar('Data pemesanan berhasil dimuat.'); // Konfirmasi berhasil
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat pemesanan. Silakan login ulang.',
        ); // Menggunakan showSnackBar global
        setState(() => _isLoading = false);
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
        'Terjadi kesalahan: $error',
      ); // Menggunakan showSnackBar global
      print('Error during _fetchPemesanan: $error'); // Debug print error
      setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Pemesanan Saya')), // Menambahkan const
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(),
            ) // Menambahkan const
          : _pemesanans.isEmpty
          ? const Center(
              child: Text('Tidak ada pemesanan.'),
            ) // Menambahkan const
          : RefreshIndicator(
              onRefresh: _fetchPemesanan, // Menambahkan RefreshIndicator
              child: ListView.builder(
                itemCount: _pemesanans.length,
                itemBuilder: (context, index) {
                  final pemesanan = _pemesanans[index];
                  return Card(
                    margin: const EdgeInsets.all(8.0), // Menambahkan const
                    child: Padding(
                      padding: const EdgeInsets.all(16.0), // Menambahkan const
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // PERBAIKAN: Menampilkan judul dan penulis buku
                          Text(
                            'Buku: ${pemesanan.buku?.judul ?? 'N/A'}',
                            style: const TextStyle(fontWeight: FontWeight.bold),
                          ),
                          Text('Penulis: ${pemesanan.buku?.penulis ?? 'N/A'}'),
                          const SizedBox(height: 4.0), // Menambahkan const
                          Text(
                            'Tanggal Pesan: ${DateFormat('yyyy-MM-dd').format(pemesanan.tanggalPesan)}',
                          ),
                          // Catatan: Model Pemesanan API Anda tidak memiliki tanggal_kembali
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
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          // Navigasi ke layar untuk membuat permintaan pemesanan baru
          final result = await Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) =>
                  const PemesananCreateScreen(), // Menggunakan nama file yang sesuai
            ),
          );
          // Refresh daftar setelah kembali dari layar pembuatan pemesanan
          if (result == true) {
            _fetchPemesanan();
          }
        },
        child: const Icon(Icons.add), // Menambahkan const
      ),
    );
  }
}
