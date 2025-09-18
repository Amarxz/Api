import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart'; // Import untuk DateFormat

import '../../../data/models/notifikasi_model.dart';
import '../../../common/utils.dart'; // Untuk showSnackBar
import '../../../data/providers/auth_provider.dart'; // Untuk mendapatkan token
// import 'notifikasi_create_screen.dart'; // Dihapus karena ini tampilan anggota, tidak ada tombol tambah

class NotifikasiScreen extends StatefulWidget {
  const NotifikasiScreen({super.key});

  @override
  _NotifikasiScreenState createState() => _NotifikasiScreenState();
}

class _NotifikasiScreenState extends State<NotifikasiScreen> {
  List<Notifikasi> _notifikasis = [];
  bool _isLoading = true;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchNotifikasi();
  }

  Future<void> _fetchNotifikasi() async {
    setState(() {
      _isLoading = true;
    });

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final String? token = authProvider.token;

    if (token == null) {
      setState(() => _isLoading = false);
      showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
      return;
    }

    try {
      print('Fetching notifications from: $_baseUrl/notifikasis');
      print(
        'Headers being sent: {Authorization: Bearer $token, Accept: application/json}',
      );

      final response = await http.get(
        Uri.parse('$_baseUrl/notifikasis'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      print('Response status for notifications: ${response.statusCode}');
      print('Response body for notifications: ${response.body}');

      if (response.statusCode == 200) {
        final dynamic decodedResponse = json.decode(response.body);

        List<dynamic> data;
        if (decodedResponse is List) {
          data = decodedResponse; // API mengembalikan list langsung
          print('API responded with a direct list for notifications.');
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data =
              decodedResponse['data']; // API mengembalikan map dengan kunci 'data'
          print(
            'API responded with a map containing a "data" key for notifications.',
          );
        } else {
          showSnackBar('Format data notifikasi tidak sesuai.');
          setState(() => _isLoading = false);
          print(
            'Error: Unexpected API response format for notifications. Response was not a List or a Map with a "data" key.',
          );
          return;
        }

        setState(() {
          _notifikasis = data.map((json) => Notifikasi.fromJson(json)).toList();
          _isLoading = false;
        });
        showSnackBar('Data notifikasi berhasil dimuat.');
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat notifikasi. Silakan login ulang.',
        );
        setState(() => _isLoading = false);
      } else {
        showSnackBar('Gagal memuat notifikasi: ${response.statusCode}');
        print(
          'Error: API returned status code ${response.statusCode} for notifications.',
        );
        setState(() => _isLoading = false);
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan: $error');
      print('Error during _fetchNotifikasi: $error');
      setState(() => _isLoading = false);
    }
  }

  // Fungsi untuk menandai notifikasi sebagai sudah dibaca
  Future<void> _markAsRead(int notifikasiId) async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final String? token = authProvider.token;

    if (token == null) {
      showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
      return;
    }

    try {
      final response = await http.put(
        Uri.parse('$_baseUrl/notifikasis/$notifikasiId'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
        body: jsonEncode({'dibaca': true}), // Kirim status dibaca = true
      );

      if (response.statusCode == 200) {
        showSnackBar('Notifikasi ditandai sebagai sudah dibaca.');
        _fetchNotifikasi(); // Refresh daftar notifikasi
      } else {
        final responseData = json.decode(response.body);
        showSnackBar(
          'Gagal menandai notifikasi: ${responseData['message'] ?? 'Terjadi kesalahan'}',
        );
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat menandai notifikasi: $error');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Notifikasi'), // Judul untuk anggota/user
        // Tombol tambah notifikasi dihapus karena ini tampilan anggota, tidak ada tombol tambah
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _notifikasis.isEmpty
          ? const Center(child: Text('Tidak ada notifikasi.'))
          : RefreshIndicator(
              onRefresh: _fetchNotifikasi, // Tambahkan refresh
              child: ListView.builder(
                itemCount: _notifikasis.length,
                itemBuilder: (context, index) {
                  final notifikasi = _notifikasis[index];
                  return Card(
                    margin: const EdgeInsets.all(8.0),
                    color: notifikasi.dibaca
                        ? Colors.white
                        : Colors
                              .blue
                              .shade50, // Warna berbeda untuk yang belum dibaca
                    child: ListTile(
                      title: Text(
                        notifikasi.pesan,
                        style: TextStyle(
                          fontWeight: notifikasi.dibaca
                              ? FontWeight.normal
                              : FontWeight.bold,
                          color: notifikasi.dibaca
                              ? Colors.black87
                              : Colors.blue.shade900,
                        ),
                      ),
                      subtitle: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // PERBAIKAN: Hanya tampilkan waktu, hapus baris "Dari:"
                          if (notifikasi.createdAt != null)
                            Text(
                              'Waktu: ${DateFormat('dd MMM, HH:mm').format(notifikasi.createdAt!)}',
                              style: const TextStyle(
                                fontSize: 12,
                                color: Colors.grey,
                              ),
                            ),
                        ],
                      ),
                      // PERBAIKAN: Menampilkan tanda ceklis berdasarkan status dibaca
                      trailing: Icon(
                        notifikasi.dibaca
                            ? Icons.done_all
                            : Icons
                                  .done, // Ceklis ganda jika dibaca, tunggal jika belum
                        color: notifikasi.dibaca
                            ? Colors.blue
                            : Colors.grey, // Warna ikon
                        size: 20,
                      ),
                      onTap: () {
                        // Tandai sebagai dibaca saat di-tap jika belum dibaca
                        if (!notifikasi.dibaca) {
                          _markAsRead(notifikasi.id);
                        }
                        // Anda bisa menambahkan navigasi ke detail notifikasi jika ada
                        // Contoh: Navigator.push(context, MaterialPageRoute(builder: (context) => NotifikasiEditScreen(notifikasi: notifikasi)));
                      },
                    ),
                  );
                },
              ),
            ),
    );
  }
}
