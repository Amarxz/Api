import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart'; // Import shared_preferences
import 'package:image_picker/image_picker.dart'; // Import image_picker
import 'dart:io'; // Untuk File
import 'package:intl/intl.dart'; // Import intl untuk format tanggal

import '../../../data/models/kategori_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart

class BukuCreateScreen extends StatefulWidget {
  const BukuCreateScreen({super.key});

  @override
  _BukuCreateScreenState createState() => _BukuCreateScreenState();
}

class _BukuCreateScreenState extends State<BukuCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  final _judulController = TextEditingController();
  final _penulisController = TextEditingController();
  final _publisherController =
      TextEditingController(); // New: Controller for publisher
  final _tanggalPublikasiController =
      TextEditingController(); // New: Controller for tanggal_publikasi
  int? _kategoriId;
  final _stokTotalController = TextEditingController();
  File? _imageFile; // New: To store the picked image file

  bool _isLoading = false;
  final String _baseUrl = 'http://10.0.2.2:8000/api';
  List<Kategori> _kategoris = [];
  bool _loadingKategori = true;

  @override
  void initState() {
    super.initState();
    _fetchKategoris();
  }

  @override
  void dispose() {
    _judulController.dispose();
    _penulisController.dispose();
    _publisherController.dispose(); // Dispose new controller
    _tanggalPublikasiController.dispose(); // Dispose new controller
    _stokTotalController.dispose();
    super.dispose();
  }

  // Function to get authentication token from SharedPreferences
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    print(
      'Auth Token retrieved from SharedPreferences in BukuCreateScreen: $token',
    ); // Debug print
    return token;
  }

  // Function to pick an image from gallery
  Future<void> _pickImage() async {
    final ImagePicker picker = ImagePicker();
    final XFile? pickedFile = await picker.pickImage(
      source: ImageSource.gallery,
    );

    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
      });
    }
  }

  // Function to select publication date
  Future<void> _selectDate(BuildContext context) async {
    DateTime initialDate = DateTime.now();
    if (_tanggalPublikasiController.text.isNotEmpty) {
      try {
        initialDate = DateTime.parse(_tanggalPublikasiController.text);
      } catch (e) {
        print('Error parsing initial date for date picker: $e');
        initialDate = DateTime.now(); // Fallback if parsing fails
      }
    }

    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: initialDate,
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() {
        _tanggalPublikasiController.text = DateFormat(
          'yyyy-MM-dd',
        ).format(picked);
      });
    }
  }

  Future<void> _fetchKategoris() async {
    setState(() {
      _loadingKategori = true;
    });
    try {
      final String? authToken = await _getAuthToken();
      if (authToken == null) {
        showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
        setState(() => _loadingKategori = false);
        return;
      }

      print('Fetching categories for dropdown from: $_baseUrl/kategoris');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
      );

      final response = await http.get(
        Uri.parse('$_baseUrl/kategoris'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
        },
      );

      print('Response status for categories: ${response.statusCode}');
      print('Response body for categories: ${response.body}');

      if (response.statusCode == 200) {
        final dynamic decodedResponse = json.decode(response.body);

        List<dynamic> data;
        if (decodedResponse is List) {
          data = decodedResponse;
          print('API responded with a direct list for categories.');
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data = decodedResponse['data'];
          print(
            'API responded with a map containing a "data" key for categories.',
          );
        } else {
          showSnackBar('Format data kategori tidak sesuai.');
          setState(() => _loadingKategori = false);
          print(
            'Error: Unexpected API response format for categories. Response was not a List or a Map with a "data" key.',
          );
          return;
        }

        setState(() {
          _kategoris = data.map((json) => Kategori.fromJson(json)).toList();
          _loadingKategori = false;
        });
        showSnackBar('Data kategori berhasil dimuat.');
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat kategori. Silakan login ulang.',
        );
        setState(() => _loadingKategori = false);
      } else {
        showSnackBar('Gagal memuat data kategori: ${response.statusCode}');
        setState(() => _loadingKategori = false);
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat memuat kategori: $error');
      print('Error during _fetchKategoris: $error');
      setState(() => _loadingKategori = false);
    }
  }

  Future<void> _createBuku() async {
    if (_formKey.currentState!.validate() && _kategoriId != null) {
      setState(() {
        _isLoading = true;
      });
      try {
        final String? authToken = await _getAuthToken();
        if (authToken == null) {
          showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
          setState(() {
            _isLoading = false;
          });
          return;
        }

        var request = http.MultipartRequest(
          'POST', // Use POST for MultipartRequest
          Uri.parse('$_baseUrl/bukus'),
        );

        request.headers.addAll({'Authorization': 'Bearer $authToken'});

        // Add text fields
        request.fields['judul'] = _judulController.text;
        request.fields['penulis'] = _penulisController.text;
        request.fields['kategori_id'] = _kategoriId.toString();
        request.fields['stok_total'] = _stokTotalController.text;
        request.fields['stok_saat_ini'] =
            _stokTotalController.text; // Initially same as stok_total
        request.fields['publisher'] =
            _publisherController.text; // Add publisher
        request.fields['tanggal_publikasi'] =
            _tanggalPublikasiController.text; // Add tanggal_publikasi

        // Add cover file if selected
        if (_imageFile != null) {
          request.files.add(
            await http.MultipartFile.fromPath(
              'cover', // Field name must match backend
              _imageFile!.path,
              filename: _imageFile!.path.split('/').last,
            ),
          );
        }

        print('Creating buku at: ${request.url}');
        print('Headers being sent: ${request.headers}');
        print('Fields being sent: ${request.fields}');
        print('Files being sent: ${request.files.map((f) => f.filename)}');

        final streamedResponse = await request.send();
        final response = await http.Response.fromStream(streamedResponse);

        print('Response status for create buku: ${response.statusCode}');
        print('Response body for create buku: ${response.body}');

        if (response.statusCode == 201) {
          showSnackBar('Buku berhasil ditambahkan.');
          Navigator.pop(context, true); // Go back and refresh list
        } else if (response.statusCode == 401 || response.statusCode == 403) {
          showSnackBar(
            'Autentikasi gagal saat membuat buku. Silakan login ulang.',
          );
        } else {
          final responseData = json.decode(response.body);
          showSnackBar(
            'Gagal menambahkan buku: ${responseData['message'] ?? 'Terjadi kesalahan'}',
          );
        }
      } catch (error) {
        showSnackBar('Terjadi kesalahan saat menambahkan buku: $error');
        print('Error during _createBuku: $error');
      } finally {
        setState(() {
          _isLoading = false;
        });
      }
    } else if (_kategoriId == null) {
      showSnackBar('Kategori harus dipilih.');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Tambah Buku')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            // Use ListView to allow scrolling if content is long
            children: <Widget>[
              // Book Title
              TextFormField(
                controller: _judulController,
                decoration: const InputDecoration(labelText: 'Judul Buku'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Judul buku tidak boleh kosong';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0),

              // Author
              TextFormField(
                controller: _penulisController,
                decoration: const InputDecoration(labelText: 'Penulis'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Penulis tidak boleh kosong';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0),

              // Publisher
              TextFormField(
                controller: _publisherController,
                decoration: const InputDecoration(labelText: 'Penerbit'),
                validator: (value) {
                  return null; // Publisher is nullable in backend
                },
              ),
              const SizedBox(height: 16.0),

              // Publication Date
              TextFormField(
                controller: _tanggalPublikasiController,
                decoration: InputDecoration(
                  labelText: 'Tanggal Publikasi (YYYY-MM-DD)',
                  suffixIcon: IconButton(
                    icon: const Icon(Icons.calendar_today),
                    onPressed: () => _selectDate(context),
                  ),
                ),
                readOnly: true, // Make field read-only to force date picker
                validator: (value) {
                  return null; // Publication date is nullable in backend
                },
                onTap: () =>
                    _selectDate(context), // Allow date selection on tap
              ),
              const SizedBox(height: 16.0),

              // Category Dropdown
              _loadingKategori
                  ? const Center(child: CircularProgressIndicator())
                  : DropdownButtonFormField<int>(
                      decoration: const InputDecoration(labelText: 'Kategori'),
                      value: _kategoriId,
                      items: _kategoris.map((Kategori kategori) {
                        return DropdownMenuItem<int>(
                          value: kategori.id,
                          child: Text(kategori.nama),
                        );
                      }).toList(),
                      onChanged: (int? newValue) {
                        setState(() {
                          _kategoriId = newValue;
                        });
                      },
                      validator: (value) {
                        if (value == null) {
                          return 'Kategori harus dipilih';
                        }
                        return null;
                      },
                    ),
              const SizedBox(height: 16.0),

              // Total Stock
              TextFormField(
                controller: _stokTotalController,
                decoration: const InputDecoration(labelText: 'Stok Total'),
                keyboardType: TextInputType.number,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Stok total tidak boleh kosong';
                  }
                  if (int.tryParse(value) == null) {
                    return 'Masukkan angka yang valid';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0),

              // Cover Image Section
              const Text(
                'Cover Buku:',
                style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8.0),
              _imageFile != null
                  ? Image.file(_imageFile!, height: 150, fit: BoxFit.cover)
                  : const Text('Tidak ada cover dipilih.'),
              const SizedBox(height: 8.0),
              ElevatedButton.icon(
                onPressed: _pickImage,
                icon: const Icon(Icons.photo_library),
                label: const Text('Pilih Cover'),
              ),
              const SizedBox(height: 24.0),

              // Save Button
              SizedBox(
                width: double.infinity,
                child: _isLoading
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        onPressed: _createBuku,
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
