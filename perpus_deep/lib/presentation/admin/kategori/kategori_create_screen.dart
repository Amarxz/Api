import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart'; // Import shared_preferences
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart (kini digunakan)

class KategoriCreateScreen extends StatefulWidget {
  const KategoriCreateScreen({
    super.key,
  }); // Menambahkan const constructor jika tidak ada

  @override
  _KategoriCreateScreenState createState() => _KategoriCreateScreenState();
}

class _KategoriCreateScreenState extends State<KategoriCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  final _namaController = TextEditingController();
  bool _isLoading = false;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

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

  // Fungsi untuk mendapatkan token dari SharedPreferences
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    // Menggunakan kunci 'token' yang konsisten dengan AuthProvider
    final token = prefs.getString('token');
    print(
      'Auth Token retrieved from SharedPreferences in KategoriCreateScreen: $token',
    ); // Debug print
    return token;
  }

  Future<void> _createKategori() async {
    if (_formKey.currentState!.validate()) {
      setState(() {
        _isLoading = true;
      });
      try {
        final String? authToken = await _getAuthToken(); // Dapatkan token
        if (authToken == null) {
          showSnackBar(
            'Anda belum login. Silakan login terlebih dahulu.',
          ); // Menggunakan showSnackBar global
          setState(() {
            _isLoading = false;
          });
          // Opsi: Arahkan pengguna ke halaman login
          // Navigator.pushReplacementNamed(context, '/login');
          return;
        }

        // Debug print: URL dan Header yang dikirim
        print('Creating category at: $_baseUrl/kategoris');
        print(
          'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
        );

        final response = await http.post(
          Uri.parse('$_baseUrl/kategoris'),
          headers: {
            'Content-Type': 'application/json',
            'Authorization':
                'Bearer $authToken', // Tambahkan header Authorization
          },
          body: jsonEncode({'nama': _namaController.text}),
        );

        // Debug print: Respons dari API
        print('Response status: ${response.statusCode}');
        print('Response body: ${response.body}');

        if (response.statusCode == 201) {
          showSnackBar(
            'Kategori berhasil ditambahkan.',
          ); // Menggunakan showSnackBar global
          Navigator.pop(context, true); // Return true untuk refresh list
        } else if (response.statusCode == 401 || response.statusCode == 403) {
          showSnackBar(
            'Autentikasi gagal. Silakan login ulang.',
          ); // Menggunakan showSnackBar global
          // Opsi: Hapus token dan arahkan ke halaman login
          // final prefs = await SharedPreferences.getInstance();
          // prefs.remove('token');
          // Navigator.pushReplacementNamed(context, '/login');
        } else {
          final responseData = json.decode(response.body);
          showSnackBar(
            'Gagal menambahkan kategori: ${responseData['message'] ?? 'Terjadi kesalahan'}', // Menggunakan showSnackBar global
          );
        }
      } catch (error) {
        showSnackBar(
          'Terjadi kesalahan saat menambahkan kategori: $error',
        ); // Menggunakan showSnackBar global
        print('Error during _createKategori: $error'); // Debug print error
      } finally {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  void dispose() {
    _namaController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Tambah Kategori')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              TextFormField(
                controller: _namaController,
                decoration: const InputDecoration(
                  labelText: 'Nama Kategori',
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Nama kategori tidak boleh kosong';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 24.0),
              SizedBox(
                width: double.infinity,
                child: _isLoading
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        onPressed: _createKategori,
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 16.0),
                        ),
                        child: const Text('Simpan'),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
