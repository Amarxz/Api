import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:intl/intl.dart'; // Import for date formatting
import 'package:provider/provider.dart'; // Import Provider

import '../../../data/models/buku_model.dart'; // For Buku model (to fetch and display book details)
import '../../../common/utils.dart'; // For showSnackBar
import '../../../data/providers/auth_provider.dart'; // To get the token

class PemesananCreateScreen extends StatefulWidget {
  const PemesananCreateScreen({super.key});

  @override
  _PemesananCreateScreenState createState() => _PemesananCreateScreenState();
}

class _PemesananCreateScreenState extends State<PemesananCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  // Controller for the order date
  late TextEditingController _tanggalPesanController;
  
  int? _selectedBukuId; // ID of the selected book from the dropdown
  List<Buku> _bukus = []; // List of books for the dropdown
  
  bool _isLoading = false; // Loading status for the submission process
  bool _loadingBuku = true; // Loading status for fetching the book list
  
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    // Initialize date controller with today's date as default
    _tanggalPesanController = TextEditingController(text: DateFormat('yyyy-MM-dd').format(DateTime.now()));
    _fetchBuku(); // Load the list of books when the screen initializes
  }

  @override
  void dispose() {
    _tanggalPesanController.dispose();
    super.dispose();
  }

  // Function to load the list of books from the API
  Future<void> _fetchBuku() async {
    setState(() {
      _loadingBuku = true;
    });
    try {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final String? authToken = authProvider.token;

      if (authToken == null) {
        showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
        setState(() => _loadingBuku = false);
        return;
      }

      print('Fetching books for ordering from: $_baseUrl/bukus');
      print('Headers being sent: {Authorization: Bearer $authToken, Accept: application/json}');

      final response = await http.get(
        Uri.parse('$_baseUrl/bukus'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
          'Accept': 'application/json',
        },
      );

      print('Response status for books: ${response.statusCode}');
      print('Response body for books: ${response.body}');

      if (response.statusCode == 200) {
        final dynamic decodedResponse = json.decode(response.body);
        List<dynamic> data;

        if (decodedResponse is List) {
          data = decodedResponse;
        } else if (decodedResponse is Map && decodedResponse.containsKey('data') && decodedResponse['data'] is List) {
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
        showSnackBar('Autentikasi gagal saat memuat daftar buku. Silakan login ulang.');
        setState(() => _loadingBuku = false);
      } else {
        showSnackBar('Gagal memuat daftar buku: ${response.statusCode}');
        setState(() => _loadingBuku = false);
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat memuat daftar buku: $error');
      print('Error during _fetchBuku in PemesananCreateScreen: $error');
      setState(() => _loadingBuku = false);
    }
  }

  // Function to select the order date
  Future<void> _selectTanggalPesan(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime(2101),
    );
    if (picked != null) {
      setState(() {
        _tanggalPesanController.text = DateFormat('yyyy-MM-dd').format(picked);
      });
    }
  }

  // Function to submit a new order
  Future<void> _createPemesanan() async {
    if (_formKey.currentState!.validate() && _selectedBukuId != null) {
      setState(() {
        _isLoading = true;
      });
      try {
        final authProvider = Provider.of<AuthProvider>(context, listen: false);
        final String? authToken = authProvider.token;

        if (authToken == null) {
          showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
          setState(() {
            _isLoading = false;
          });
          return;
        }

        final response = await http.post(
          Uri.parse('$_baseUrl/pemesanans'),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $authToken',
            'Accept': 'application/json',
          },
          body: jsonEncode({
            'buku_id': _selectedBukuId,
            'tanggal_pesan': _tanggalPesanController.text,
            // Perhatikan: Model Pemesanan API Anda tidak memiliki tanggal_kembali
            // Pastikan Anda tidak mengirim field yang tidak ada di API jika tidak diperlukan.
          }),
        );

        print('Response status for create pemesanan: ${response.statusCode}');
        print('Response body for create pemesanan: ${response.body}');

        if (response.statusCode == 201) {
          showSnackBar('Pemesanan berhasil diajukan!');
          Navigator.pop(context, true); // Go back and signal to refresh the list
        } else if (response.statusCode == 401 || response.statusCode == 403) {
          showSnackBar('Autentikasi gagal saat mengajukan pemesanan. Silakan login ulang.');
        } else {
          final responseData = json.decode(response.body);
          showSnackBar('Gagal mengajukan pemesanan: ${responseData['message'] ?? 'Terjadi kesalahan'}');
        }
      } catch (error) {
        showSnackBar('Terjadi kesalahan saat mengajukan pemesanan: $error');
        print('Error during _createPemesanan: $error');
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
      appBar: AppBar(title: const Text('Buat Pemesanan Baru')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              // Dropdown to select a Book
              _loadingBuku
                  ? const Center(child: CircularProgressIndicator())
                  : DropdownButtonFormField<int>(
                      decoration: const InputDecoration(labelText: 'Pilih Buku'),
                      value: _selectedBukuId,
                      items: _bukus.map((Buku buku) {
                        return DropdownMenuItem<int>(
                          value: buku.id,
                          // Display book title and author, as the Buku model provides them
                          child: Text('${buku.judul} (${buku.penulis}) - Stok: ${buku.stokSaatIni}'),
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

              // Order Date Field
              TextFormField(
                controller: _tanggalPesanController,
                decoration: InputDecoration(
                  labelText: 'Tanggal Pesan',
                  suffixIcon: IconButton(
                    icon: const Icon(Icons.calendar_today),
                    onPressed: () => _selectTanggalPesan(context),
                  ),
                ),
                readOnly: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Tanggal pesan tidak boleh kosong';
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
                        onPressed: _createPemesanan,
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 16.0),
                        ),
                        child: const Text('Buat Pemesanan'),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
