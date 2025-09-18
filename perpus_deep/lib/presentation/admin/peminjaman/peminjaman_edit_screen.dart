import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:intl/intl.dart'; // Import for date formatting
// import 'package:shared_preferences/shared_preferences.dart'; // Dihapus karena tidak digunakan langsung di sini
import '../../../data/models/peminjaman_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart (kini digunakan)
import 'package:provider/provider.dart'; // Tambahkan ini untuk Provider.of
import '../../../data/providers/auth_provider.dart'; // PERBAIKAN: Tambahkan import untuk AuthProvider

class PeminjamanEditScreen extends StatefulWidget {
  final Peminjaman peminjaman;

  const PeminjamanEditScreen({super.key, required this.peminjaman});

  @override
  _PeminjamanEditScreenState createState() => _PeminjamanEditScreenState();
}

class _PeminjamanEditScreenState extends State<PeminjamanEditScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _tanggalPinjamController;
  late TextEditingController _tanggalKembaliController;
  bool _isLoading = false;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _tanggalPinjamController = TextEditingController(
      text: DateFormat('yyyy-MM-dd').format(widget.peminjaman.tanggalPinjam),
    );
    // Menangani null untuk tanggalKembali
    _tanggalKembaliController = TextEditingController(
      text: widget.peminjaman.tanggalKembali != null
          ? DateFormat('yyyy-MM-dd').format(widget.peminjaman.tanggalKembali!)
          : '', // Jika null, kosongkan atau beri string default
    );
  }

  @override
  void dispose() {
    _tanggalPinjamController.dispose();
    _tanggalKembaliController.dispose();
    super.dispose();
  }

  // Fungsi _getAuthToken ini tidak lagi diperlukan karena token sudah diambil dari AuthProvider
  /*
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    print('Auth Token retrieved from SharedPreferences in PeminjamanEditScreen: $token');
    return token;
  }
  */

  Future<void> _selectTanggalKembali(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: widget.peminjaman.tanggalKembali ?? DateTime.now(),
      firstDate: widget
          .peminjaman
          .tanggalPinjam, // Tanggal kembali tidak boleh sebelum tanggal pinjam
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

  Future<void> _updatePeminjaman() async {
    if (_formKey.currentState!.validate()) {
      setState(() {
        _isLoading = true;
      });
      try {
        final authProvider = Provider.of<AuthProvider>(
          context,
          listen: false,
        ); // Ambil AuthProvider
        final String? authToken =
            authProvider.token; // Ambil token dari AuthProvider

        if (authToken == null) {
          showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
          setState(() {
            _isLoading = false;
          });
          return;
        }

        // Pastikan tanggal kembali diisi, jika tidak, kirim null atau string kosong ke API
        final String? tanggalKembaliValue =
            _tanggalKembaliController.text.isEmpty
            ? null
            : _tanggalKembaliController.text;

        // Debug print: URL dan Header yang dikirim
        print(
          'Updating peminjaman at: $_baseUrl/peminjamans/${widget.peminjaman.id}',
        );
        print(
          'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken, Accept: application/json}', // Tambahkan Accept header
        );
        print(
          'Body being sent: ${jsonEncode({'tanggal_kembali': tanggalKembaliValue})}',
        );

        final response = await http.put(
          Uri.parse('$_baseUrl/peminjamans/${widget.peminjaman.id}'),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $authToken',
            'Accept': 'application/json', // Tambahkan header ini
          },
          body: jsonEncode({
            'tanggal_kembali': tanggalKembaliValue,
            // Anda mungkin perlu mengirim semua field yang diizinkan untuk diupdate oleh API
            // Misalnya: 'user_id': widget.peminjaman.userId, 'buku_id': widget.peminjaman.bukuId, dll.
            // Namun, untuk edit tanggal kembali, biasanya cukup mengirim field yang diubah.
          }),
        );

        print('Response status for update peminjaman: ${response.statusCode}');
        print('Response body for update peminjaman: ${response.body}');

        if (response.statusCode == 200) {
          showSnackBar('Peminjaman berhasil diperbarui.');
          Navigator.pop(context, true); // Return true untuk refresh list
        } else if (response.statusCode == 401 || response.statusCode == 403) {
          showSnackBar(
            'Autentikasi gagal saat memperbarui peminjaman. Silakan login ulang.',
          );
        } else {
          // Tangani respons error dari API yang mungkin berformat JSON
          final dynamic responseData = json.decode(response.body);
          showSnackBar(
            'Gagal memperbarui peminjaman: ${responseData['message'] ?? 'Terjadi kesalahan'}',
          );
        }
      } catch (error) {
        showSnackBar('Terjadi kesalahan saat memperbarui peminjaman: $error');
        print('Error during _updatePeminjaman: $error');
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
      appBar: AppBar(title: const Text('Edit Peminjaman')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              TextFormField(
                controller: _tanggalPinjamController,
                decoration: const InputDecoration(
                  labelText: 'Tanggal Pinjam (Tidak Bisa Diubah)',
                ),
                readOnly: true, // Tidak bisa diubah
              ),
              const SizedBox(height: 16.0),
              TextFormField(
                controller: _tanggalKembaliController,
                decoration: InputDecoration(
                  labelText: 'Tanggal Kembali',
                  suffixIcon: IconButton(
                    icon: const Icon(Icons.calendar_today),
                    onPressed: () => _selectTanggalKembali(context),
                  ),
                ),
                readOnly: true, // Hanya bisa dipilih lewat date picker
                validator: (value) {
                  // Jika tanggal kembali wajib, Anda bisa tambahkan validasi di sini
                  // if (value == null || value.isEmpty) {
                  //   return 'Tanggal kembali tidak boleh kosong';
                  // }
                  return null;
                },
              ),
              const SizedBox(height: 24.0),
              SizedBox(
                width: double.infinity,
                child: _isLoading
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        onPressed: _updatePeminjaman,
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
