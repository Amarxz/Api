import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart'; // Import shared_preferences
import '../../../data/models/kategori_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart (kini digunakan)
import 'kategori_create_screen.dart';
import 'kategori_edit_screen.dart';

class KategoriListScreen extends StatefulWidget {
  const KategoriListScreen({super.key});

  @override
  _KategoriListScreenState createState() => _KategoriListScreenState();
}

class _KategoriListScreenState extends State<KategoriListScreen> {
  List<Kategori> _kategoris = [];
  bool _isLoading = true;
  // Gunakan IP lokal yang sesuai untuk emulator Android
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchKategoris();
  }

  // Hapus _showSnackBar lokal, karena kita akan menggunakan yang dari utils.dart
  /*
  void _showSnackBar(String message) {
    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(message),
          duration: const Duration(seconds: 3),
          behavior: SnackBarBehavior.floating,
        ),
      );
    }
  }
  */

  // Fungsi untuk mendapatkan token dari SharedPreferences (diulang dari KategoriCreateScreen)
  // Dalam aplikasi nyata, ini bisa dipindahkan ke service/repository terpisah
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    // Menggunakan kunci 'token' yang sama dengan AuthProvider
    final token = prefs.getString('token');
    // Debug print
    print(
      'Auth Token retrieved from SharedPreferences in KategoriListScreen: $token',
    );
    return token;
  }

  Future<void> _fetchKategoris() async {
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
      print('Fetching categories from: $_baseUrl/kategoris');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
      );

      final response = await http.get(
        Uri.parse('$_baseUrl/kategoris'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization':
              'Bearer $authToken', // Tambahkan header Authorization
        },
      );

      // Debug print: Respons dari API
      print('Response status for categories: ${response.statusCode}');
      print('Response body for categories: ${response.body}');

      if (response.statusCode == 200) {
        // PERBAIKAN: Tangani respons API yang bisa berupa List<dynamic> langsung atau Map<String, dynamic> dengan kunci 'data'
        final dynamic decodedResponse = json.decode(response.body);

        List<dynamic> data;
        if (decodedResponse is List) {
          data = decodedResponse; // API mengembalikan list langsung
          print('API responded with a direct list for categories.');
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data =
              decodedResponse['data']; // API mengembalikan map dengan kunci 'data'
          print(
            'API responded with a map containing a "data" key for categories.',
          );
        } else {
          showSnackBar(
            'Format data kategori tidak sesuai.',
          ); // Menggunakan showSnackBar global
          setState(() => _isLoading = false);
          print(
            'Error: Unexpected API response format for categories. Response was not a List or a Map with a "data" key.',
          );
          return;
        }

        setState(() {
          _kategoris = data.map((json) => Kategori.fromJson(json)).toList();
          _isLoading = false;
        });
        showSnackBar(
          'Data kategori berhasil dimuat.',
        ); // Konfirmasi berhasil, menggunakan showSnackBar global
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal. Silakan login ulang.',
        ); // Menggunakan showSnackBar global
        setState(() => _isLoading = false);
        // Implementasi logika untuk logout dan arahkan ke halaman login
        // final prefs = await SharedPreferences.getInstance();
        // await prefs.remove('token'); // Hapus token dengan kunci 'token'
        // Navigator.pushReplacementNamed(context, '/login');
      } else {
        final responseData = json.decode(response.body);
        showSnackBar(
          'Gagal memuat data kategori: ${responseData['message'] ?? 'Status Code: ${response.statusCode}'}', // Menggunakan showSnackBar global
        );
        setState(() => _isLoading = false);
        print(
          'Error: API returned status code ${response.statusCode} for categories.',
        );
      }
    } catch (error) {
      showSnackBar(
        'Terjadi kesalahan saat memuat kategori: $error',
      ); // Menggunakan showSnackBar global
      // Debug print error
      print('Error during _fetchKategoris: $error');
      setState(() => _isLoading = false);
    }
  }

  Future<void> _deleteKategori(int id) async {
    try {
      final String? authToken = await _getAuthToken();
      if (authToken == null) {
        showSnackBar(
          'Anda belum login. Silakan login terlebih dahulu.',
        ); // Menggunakan showSnackBar global
        return;
      }

      // Debug print: URL dan Header yang dikirim
      print('Deleting category from: $_baseUrl/kategoris/$id');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
      );

      final response = await http.delete(
        Uri.parse('$_baseUrl/kategoris/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization':
              'Bearer $authToken', // Tambahkan header Authorization
        },
      );

      // Debug print: Respons dari API
      print('Delete Response status: ${response.statusCode}');
      print('Delete Response body: ${response.body}');

      if (response.statusCode == 200) {
        showSnackBar(
          'Kategori berhasil dihapus.',
        ); // Menggunakan showSnackBar global
        _fetchKategoris(); // Refresh list
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal. Silakan login ulang.',
        ); // Menggunakan showSnackBar global
        // Implementasi logika untuk logout dan arahkan ke halaman login
      } else {
        final responseData = json.decode(response.body);
        showSnackBar(
          'Gagal menghapus kategori: ${responseData['message'] ?? 'Terjadi kesalahan'}', // Menggunakan showSnackBar global
        );
      }
    } catch (error) {
      showSnackBar(
        'Terjadi kesalahan saat menghapus kategori: $error',
      ); // Menggunakan showSnackBar global
      // Debug print error
      print('Error during _deleteKategori: $error');
    }
  }

  void _showDeleteConfirmation(Kategori kategori) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Konfirmasi Hapus'),
          content: Text(
            'Apakah Anda yakin ingin menghapus kategori "${kategori.nama}"?',
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Batal'),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
                _deleteKategori(kategori.id);
              },
              child: const Text('Hapus', style: TextStyle(color: Colors.red)),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Kelola Kategori'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () async {
              final result = await Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => KategoriCreateScreen(),
                ), // Hapus 'const' di sini
              );
              // Refresh list setelah kembali dari create screen
              if (result == true) {
                _fetchKategoris();
              }
            },
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _kategoris.isEmpty
          ? const Center(child: Text('Tidak ada kategori.'))
          : RefreshIndicator(
              onRefresh: _fetchKategoris,
              child: ListView.builder(
                itemCount: _kategoris.length,
                itemBuilder: (context, index) {
                  final kategori = _kategoris[index];
                  return Card(
                    margin: const EdgeInsets.all(8.0),
                    child: ListTile(
                      title: Text(
                        kategori.nama,
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      trailing: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          IconButton(
                            icon: const Icon(Icons.edit, color: Colors.blue),
                            onPressed: () async {
                              final result = await Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) => KategoriEditScreen(
                                    kategori: kategori,
                                  ), // Tidak ada 'const' di sini, sudah benar
                                ),
                              );
                              // Refresh list setelah kembali dari edit screen
                              if (result == true) {
                                _fetchKategoris();
                              }
                            },
                          ),
                          IconButton(
                            icon: const Icon(Icons.delete, color: Colors.red),
                            onPressed: () => _showDeleteConfirmation(kategori),
                          ),
                        ],
                      ),
                      onTap: () async {
                        // Alternatif: tap card untuk edit
                        final result = await Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => KategoriEditScreen(
                              kategori: kategori,
                            ), // Tidak ada 'const' di sini, sudah benar
                          ),
                        );
                        if (result == true) {
                          _fetchKategoris();
                        }
                      },
                    ),
                  );
                },
              ),
            ),
    );
  }
}
