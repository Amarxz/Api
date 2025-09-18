import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
// import 'package:shared_preferences/shared_preferences.dart'; // Dihapus karena tidak digunakan langsung di sini
import 'package:intl/intl.dart'; // Dibutuhkan untuk memformat tanggal
import 'package:provider/provider.dart'; // PERBAIKAN: Diaktifkan karena digunakan untuk Provider.of

import '../../../data/models/buku_model.dart'; // Untuk model Buku
import '../../../common/utils.dart'; // Untuk showSnackBar
import '../../../data/providers/auth_provider.dart'; // Untuk mendapatkan token (via Provider.of)

class PeminjamanCreateScreen extends StatefulWidget {
  const PeminjamanCreateScreen({super.key});

  @override
  _PeminjamanCreateScreenState createState() => _PeminjamanCreateScreenState();
}

class _PeminjamanCreateScreenState extends State<PeminjamanCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _tanggalPinjamController;
  late TextEditingController _tanggalKembaliController;

  int? _selectedBukuId; // ID buku yang dipilih dari dropdown
  List<Buku> _bukus = []; // Daftar buku untuk dropdown

  bool _isLoading = false;
  bool _loadingBuku = true;

  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _tanggalPinjamController = TextEditingController(
      text: DateFormat('yyyy-MM-dd').format(DateTime.now()),
    );
    _tanggalKembaliController = TextEditingController(
      text: DateFormat(
        'yyyy-MM-dd',
      ).format(DateTime.now().add(const Duration(days: 7))),
    );
    _fetchBuku();
  }

  @override
  void dispose() {
    _tanggalPinjamController.dispose();
    _tanggalKembaliController.dispose();
    super.dispose();
  }

  // Fungsi _getAuthToken ini tidak lagi diperlukan karena token sudah diambil dari AuthProvider
  // dan Provider.of sudah digunakan di _fetchBuku dan _ajukanPeminjaman
  /*
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    print('Auth Token retrieved in PeminjamanCreateScreen: $token');
    return token;
  }
  */

  Future<void> _fetchBuku() async {
    setState(() {
      _loadingBuku = true;
    });
    try {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final String? authToken =
          authProvider.token; // Ambil token dari AuthProvider

      if (authToken == null) {
        showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
        setState(() => _loadingBuku = false);
        return;
      }

      print('Fetching books for borrowing from: $_baseUrl/bukus');
      print(
        'Headers being sent: {Authorization: Bearer $authToken, Accept: application/json}',
      ); // Tambah Accept header

      final response = await http.get(
        Uri.parse('$_baseUrl/bukus'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
          'Accept': 'application/json', // Tambahkan header ini
        },
      );

      print('Response status for books: ${response.statusCode}');
      print('Response body for books: ${response.body}');

      if (response.statusCode == 200) {
        final dynamic decodedResponse = json.decode(response.body);
        List<dynamic> data;

        if (decodedResponse is List) {
          data = decodedResponse;
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data = decodedResponse['data'];
        } else {
          showSnackBar('Format data buku tidak sesuai.');
          setState(() => _loadingBuku = false);
          return;
        }

        setState(() {
          _bukus = data.map((json) => Buku.fromJson(json)).toList();
          _loadingBuku = false;
        });
        showSnackBar('Daftar buku berhasil dimuat.');
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat daftar buku. Silakan login ulang.',
        );
        setState(() => _loadingBuku = false);
      } else {
        showSnackBar('Gagal memuat daftar buku: ${response.statusCode}');
        setState(() => _loadingBuku = false);
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat memuat daftar buku: $error');
      print('Error during _fetchBuku in PeminjamanCreateScreen: $error');
      setState(() => _loadingBuku = false);
    }
  }

  Future<void> _selectTanggalPinjam(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime(2101),
    );
    if (picked != null) {
      setState(() {
        _tanggalPinjamController.text = DateFormat('yyyy-MM-dd').format(picked);
      });
    }
  }

  Future<void> _selectTanggalKembali(BuildContext context) async {
    DateTime initialTanggalPinjam = DateTime.parse(
      _tanggalPinjamController.text,
    );
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: initialTanggalPinjam.add(const Duration(days: 7)),
      firstDate: initialTanggalPinjam,
      lastDate: DateTime(2101),
    );
    if (picked != null) {
      setState(() {
        _tanggalKembaliController.text = DateFormat(
          'yyyy-MM-dd',
        ).format(picked);
      });
    }
  }

  Future<void> _ajukanPeminjaman() async {
    if (_formKey.currentState!.validate() && _selectedBukuId != null) {
      setState(() {
        _isLoading = true;
      });
      try {
        final authProvider = Provider.of<AuthProvider>(context, listen: false);
        final String? authToken =
            authProvider.token; // Ambil token dari AuthProvider

        if (authToken == null) {
          showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
          setState(() {
            _isLoading = false;
          });
          return;
        }

        final response = await http.post(
          Uri.parse('$_baseUrl/peminjamans'),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $authToken',
            'Accept': 'application/json', // Tambahkan header ini
          },
          body: jsonEncode({
            'buku_id': _selectedBukuId,
            'tanggal_pinjam': _tanggalPinjamController.text,
            'tanggal_kembali': _tanggalKembaliController.text,
          }),
        );

        print('Response status for create peminjaman: ${response.statusCode}');
        print('Response body for create peminjaman: ${response.body}');

        if (response.statusCode == 201) {
          showSnackBar('Peminjaman berhasil diajukan!');
          Navigator.pop(context, true);
        } else if (response.statusCode == 401 || response.statusCode == 403) {
          showSnackBar(
            'Autentikasi gagal saat mengajukan peminjaman. Silakan login ulang.',
          );
        } else {
          final responseData = json.decode(
            response.body,
          ); // Ini baris yang menyebabkan FormatException
          showSnackBar(
            'Gagal mengajukan peminjaman: ${responseData['message'] ?? 'Terjadi kesalahan'}',
          );
        }
      } catch (error) {
        showSnackBar('Terjadi kesalahan saat mengajukan peminjaman: $error');
        print(
          'Error during _ajukanPeminjaman in PeminjamanCreateScreen: $error',
        );
      } finally {
        setState(() {
          _isLoading = false;
        });
      }
    } else if (_selectedBukuId == null) {
      showSnackBar('Buku harus dipilih.');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Ajukan Peminjaman')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              // Dropdown untuk memilih Buku
              _loadingBuku
                  ? const Center(child: CircularProgressIndicator())
                  : DropdownButtonFormField<int>(
                      decoration: const InputDecoration(
                        labelText: 'Pilih Buku',
                      ),
                      value: _selectedBukuId,
                      items: _bukus.map((Buku buku) {
                        return DropdownMenuItem<int>(
                          value: buku.id,
                          child: Text(
                            '${buku.judul} (${buku.penulis}) - Stok: ${buku.stokSaatIni}',
                          ),
                        );
                      }).toList(),
                      onChanged: (int? newValue) {
                        setState(() {
                          _selectedBukuId = newValue;
                        });
                      },
                      validator: (value) {
                        if (value == null) {
                          return 'Buku harus dipilih';
                        }
                        return null;
                      },
                    ),
              const SizedBox(height: 16.0),

              // Field Tanggal Pinjam
              TextFormField(
                controller: _tanggalPinjamController,
                decoration: InputDecoration(
                  labelText: 'Tanggal Pinjam',
                  suffixIcon: IconButton(
                    icon: const Icon(Icons.calendar_today),
                    onPressed: () => _selectTanggalPinjam(context),
                  ),
                ),
                readOnly: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Tanggal pinjam tidak boleh kosong';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0),

              // Field Tanggal Kembali
              TextFormField(
                controller: _tanggalKembaliController,
                decoration: InputDecoration(
                  labelText: 'Tanggal Kembali (Perkiraan)',
                  suffixIcon: IconButton(
                    icon: const Icon(Icons.calendar_today),
                    onPressed: () => _selectTanggalKembali(context),
                  ),
                ),
                readOnly: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Tanggal kembali tidak boleh kosong';
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
                        onPressed: _ajukanPeminjaman,
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 16.0),
                        ),
                        child: const Text('Ajukan Peminjaman'),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
