import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart'; // Import shared_preferences
import '../../../data/models/kategori_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart (kini digunakan)

class KategoriEditScreen extends StatefulWidget {
  final Kategori kategori;

  const KategoriEditScreen({super.key, required this.kategori});

  @override
  _KategoriEditScreenState createState() => _KategoriEditScreenState();
}

class _KategoriEditScreenState extends State<KategoriEditScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _namaController;
  bool _isLoading = false;
  // Menggunakan IP lokal yang konsisten untuk emulator Android
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _namaController = TextEditingController(text: widget.kategori.nama);
  }

  @override
  void dispose() {
    _namaController.dispose();
    super.dispose();
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

  // Fungsi untuk mendapatkan token dari SharedPreferences
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    // Menggunakan kunci 'token' yang konsisten dengan AuthProvider
    final token = prefs.getString('token');
    print(
      'Auth Token retrieved from SharedPreferences in KategoriEditScreen: $token',
    ); // Debug print
    return token;
  }

  Future<void> _updateKategori() async {
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
        print(
          'Updating category at: $_baseUrl/kategoris/${widget.kategori.id}',
        );
        print(
          'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
        );

        final response = await http.put(
          Uri.parse(
            '$_baseUrl/kategoris/${widget.kategori.id}',
          ), // Perbaikan URL
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

        if (response.statusCode == 200) {
          showSnackBar(
            'Kategori berhasil diperbarui.',
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
            'Gagal memperbarui kategori: ${responseData['message'] ?? 'Terjadi kesalahan'}', // Menggunakan showSnackBar global
          );
        }
      } catch (error) {
        showSnackBar(
          'Terjadi kesalahan saat memperbarui kategori: $error',
        ); // Menggunakan showSnackBar global
        print('Error during _updateKategori: $error'); // Debug print error
      } finally {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Edit Kategori')),
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
                        onPressed: _updateKategori,
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 16.0),
                        ),
                        child: const Text('Simpan Perubahan'),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
