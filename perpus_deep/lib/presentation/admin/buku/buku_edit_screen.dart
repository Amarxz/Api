import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart'; // Import shared_preferences
import 'package:image_picker/image_picker.dart'; // Import image_picker
import 'dart:io'; // Untuk File
import 'package:intl/intl.dart'; // Import intl untuk format tanggal

import '../../../data/models/buku_model.dart';
import '../../../data/models/kategori_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart

class BukuEditScreen extends StatefulWidget {
  final Buku buku;

  const BukuEditScreen({super.key, required this.buku});

  @override
  _BukuEditScreenState createState() => _BukuEditScreenState();
}

class _BukuEditScreenState extends State<BukuEditScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _judulController;
  late TextEditingController _penulisController;
  late TextEditingController _publisherController; // New
  late TextEditingController _tanggalPublikasiController; // New
  int? _kategoriId;
  late TextEditingController _stokTotalController;
  late TextEditingController _stokSaatIniController;
  File? _imageFile; // New: untuk gambar cover yang baru dipilih
  String? _currentCoverUrl; // New: untuk menyimpan URL cover yang sudah ada

  bool _isLoading = false;
  final String _baseUrl = 'http://10.0.2.2:8000/api';
  List<Kategori> _kategoris = [];
  bool _loadingKategori = true;

  @override
  void initState() {
    super.initState();
    _judulController = TextEditingController(text: widget.buku.judul);
    _penulisController = TextEditingController(text: widget.buku.penulis);
    _publisherController = TextEditingController(
      text: widget.buku.publisher,
    ); // Initialize publisher
    _tanggalPublikasiController = TextEditingController(
      // Initialize tanggal publikasi
      text: widget.buku.tanggalPublikasi != null
          ? DateFormat(
              'yyyy-MM-dd',
            ).format(DateTime.parse(widget.buku.tanggalPublikasi!))
          : '',
    );
    _kategoriId = widget.buku.kategoriId;
    _stokTotalController = TextEditingController(
      text: widget.buku.stokTotal.toString(),
    );
    _stokSaatIniController = TextEditingController(
      text: widget.buku.stokSaatIni.toString(),
    );

    _currentCoverUrl = widget.buku.cover; // Set current cover URL

    _fetchKategoris();
  }

  @override
  void dispose() {
    _judulController.dispose();
    _penulisController.dispose();
    _publisherController.dispose(); // Dispose new controller
    _tanggalPublikasiController.dispose(); // Dispose new controller
    _stokTotalController.dispose();
    _stokSaatIniController.dispose();
    super.dispose();
  }

  // Fungsi untuk mendapatkan token dari SharedPreferences
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    print(
      'Auth Token retrieved from SharedPreferences in BukuEditScreen: $token',
    ); // Debug print
    return token;
  }

  // Fungsi untuk memilih gambar dari galeri/kamera
  Future<void> _pickImage() async {
    final ImagePicker picker = ImagePicker();
    final XFile? pickedFile = await picker.pickImage(
      source: ImageSource.gallery,
    );

    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
        _currentCoverUrl = null; // Clear existing URL if a new image is picked
      });
    }
  }

  // Fungsi untuk memilih tanggal publikasi
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

      print(
        'Fetching categories for dropdown in BukuEditScreen from: $_baseUrl/kategoris',
      );
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

      print(
        'Response status for categories in BukuEditScreen: ${response.statusCode}',
      );
      print('Response body for categories in BukuEditScreen: ${response.body}');

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
        print(
          'Error: API returned status code ${response.statusCode} for categories.',
        );
        setState(() => _loadingKategori = false);
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat memuat kategori: $error');
      print('Error during _fetchKategoris in BukuEditScreen: $error');
      setState(() => _loadingKategori = false);
    }
  }

  Future<void> _updateBuku() async {
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
          'POST', // Menggunakan POST untuk MultipartRequest (Laravel akan menanganinya sebagai PUT/PATCH dengan _method)
          Uri.parse('$_baseUrl/bukus/${widget.buku.id}'),
        );

        request.headers.addAll({'Authorization': 'Bearer $authToken'});

        // Menambahkan _method PUT/PATCH untuk Laravel RESTful API
        request.fields['_method'] = 'PUT';

        // Menambahkan field teks
        request.fields['judul'] = _judulController.text;
        request.fields['penulis'] = _penulisController.text;
        request.fields['kategori_id'] = _kategoriId.toString();
        request.fields['stok_total'] = _stokTotalController.text;
        request.fields['stok_saat_ini'] = _stokSaatIniController.text;
        request.fields['publisher'] = _publisherController.text;
        request.fields['tanggal_publikasi'] = _tanggalPublikasiController.text;

        // Menambahkan file cover jika ada yang baru dipilih
        if (_imageFile != null) {
          request.files.add(
            await http.MultipartFile.fromPath(
              'cover', // Nama field harus sesuai dengan yang di-backend
              _imageFile!.path,
              filename: _imageFile!.path.split('/').last,
            ),
          );
        } else if (_currentCoverUrl != null && _imageFile == null) {
          // Jika cover sudah ada dan tidak ada file baru yang dipilih,
          // dan user tidak mengklik "Clear Cover", maka kirimkan null atau biarkan kosong.
          // Untuk skenario "Clear Cover", kita bisa mengirimkan string kosong.
          // Logika ini disederhanakan: jika ada currentCoverUrl dan tidak ada file baru, biarkan backend menanganinya.
          // Jika user ingin menghapus, mereka harus mengklik "Clear Cover" dan kita kirimkan string kosong.
          // Kita perlu cara untuk memberitahu backend jika cover harus dihapus
          // Jika _imageFile null DAN _currentCoverUrl juga null (setelah di clear oleh user),
          // maka kita kirimkan string kosong untuk cover agar backend menghapus yang lama.
          if (widget.buku.cover != null && _currentCoverUrl == null) {
            request.fields['cover'] =
                ''; // Kirim string kosong untuk memberitahu backend agar menghapus cover
          }
        }

        print('Updating buku at: ${request.url}');
        print('Headers being sent: ${request.headers}');
        print('Fields being sent: ${request.fields}');
        print('Files being sent: ${request.files.map((f) => f.filename)}');

        final streamedResponse = await request.send();
        final response = await http.Response.fromStream(streamedResponse);

        print('Response status for update buku: ${response.statusCode}');
        print('Response body for update buku: ${response.body}');

        if (response.statusCode == 200) {
          showSnackBar('Buku berhasil diperbarui.');
          Navigator.pop(context, true); // Kembali ke daftar buku dan refresh
        } else if (response.statusCode == 401 || response.statusCode == 403) {
          showSnackBar(
            'Autentikasi gagal saat memperbarui buku. Silakan login ulang.',
          );
        } else {
          final responseData = json.decode(response.body);
          showSnackBar(
            'Gagal memperbarui buku: ${responseData['message'] ?? 'Terjadi kesalahan'}',
          );
        }
      } catch (error) {
        showSnackBar('Terjadi kesalahan saat memperbarui buku: $error');
        print('Error during _updateBuku: $error');
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
      appBar: AppBar(title: const Text('Edit Buku')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            // Menggunakan ListView agar bisa discroll jika konten terlalu panjang
            children: <Widget>[
              // Judul Buku
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

              // Penulis
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

              // Penerbit
              TextFormField(
                controller: _publisherController,
                decoration: const InputDecoration(labelText: 'Penerbit'),
                validator: (value) {
                  return null; // Publisher is nullable in backend
                },
              ),
              const SizedBox(height: 16.0),

              // Tanggal Publikasi
              TextFormField(
                controller: _tanggalPublikasiController,
                decoration: InputDecoration(
                  labelText: 'Tanggal Publikasi (YYYY-MM-DD)',
                  suffixIcon: IconButton(
                    icon: const Icon(Icons.calendar_today),
                    onPressed: () => _selectDate(context),
                  ),
                ),
                readOnly:
                    true, // Membuat field hanya bisa dipilih dari date picker
                validator: (value) {
                  return null; // Tanggal publikasi is nullable in backend
                },
                onTap: () => _selectDate(
                  context,
                ), // Memungkinkan pemilihan tanggal saat diklik
              ),
              const SizedBox(height: 16.0),

              // Kategori
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

              // Stok Total
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

              // Stok Saat Ini
              TextFormField(
                controller: _stokSaatIniController,
                decoration: const InputDecoration(labelText: 'Stok Saat Ini'),
                keyboardType: TextInputType.number,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Stok saat ini tidak boleh kosong';
                  }
                  if (int.tryParse(value) == null) {
                    return 'Masukkan angka yang valid';
                  }
                  // Tambahan validasi: stok saat ini tidak boleh lebih dari stok total
                  if (int.tryParse(value)! >
                      int.parse(_stokTotalController.text)) {
                    return 'Stok saat ini tidak boleh melebihi stok total';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0),

              // Bagian untuk Cover Gambar
              const Text(
                'Cover Buku:',
                style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8.0),
              _imageFile != null
                  ? Image.file(_imageFile!, height: 150, fit: BoxFit.cover)
                  : (_currentCoverUrl != null && _currentCoverUrl!.isNotEmpty
                        ? Image.network(
                            '${_baseUrl.replaceAll('/api', '/storage')}/$_currentCoverUrl',
                            height: 150,
                            fit: BoxFit.cover,
                            errorBuilder: (context, error, stackTrace) {
                              return const Icon(Icons.broken_image, size: 100);
                            },
                          )
                        : const Text('Tidak ada cover dipilih.')),
              const SizedBox(height: 8.0),
              Row(
                children: [
                  ElevatedButton.icon(
                    onPressed: _pickImage,
                    icon: const Icon(Icons.photo_library),
                    label: const Text('Pilih Cover'),
                  ),
                  const SizedBox(width: 8.0),
                  if (_imageFile != null ||
                      (_currentCoverUrl != null &&
                          _currentCoverUrl!.isNotEmpty))
                    ElevatedButton.icon(
                      onPressed: () {
                        setState(() {
                          _imageFile = null; // Clear picked file
                          _currentCoverUrl = null; // Clear current URL
                        });
                        showSnackBar(
                          'Cover berhasil dihapus dari preview. Klik "Simpan Perubahan" untuk menghapus di database.',
                        );
                      },
                      icon: const Icon(Icons.clear),
                      label: const Text('Hapus Cover'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.red,
                      ),
                    ),
                ],
              ),
              const SizedBox(height: 24.0),

              // Tombol Simpan Perubahan
              SizedBox(
                width: double.infinity,
                child: _isLoading
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        onPressed: _updateBuku,
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
