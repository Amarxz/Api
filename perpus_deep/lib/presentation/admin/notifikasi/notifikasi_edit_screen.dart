import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart'; // Import untuk DateFormat

import '../../../data/models/notifikasi_model.dart';
import '../../../common/utils.dart'; // Untuk showSnackBar
import '../../../data/providers/auth_provider.dart'; // Untuk mendapatkan token

class NotifikasiEditScreen extends StatefulWidget {
  final Notifikasi notifikasi; // Menerima objek Notifikasi

  const NotifikasiEditScreen({super.key, required this.notifikasi});

  @override
  _NotifikasiEditScreenState createState() => _NotifikasiEditScreenState();
}

class _NotifikasiEditScreenState extends State<NotifikasiEditScreen> {
  bool _isLoading = false;
  late bool _dibacaStatus; // State untuk status dibaca
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _dibacaStatus = widget
        .notifikasi
        .dibaca; // Inisialisasi status dibaca dari notifikasi yang diterima
  }

  // Fungsi untuk mendapatkan token dari AuthProvider
  Future<String?> _getAuthToken() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final String? token = authProvider.token;
    print(
      'Auth Token retrieved in NotifikasiEditScreen: $token',
    ); // Debug print
    return token;
  }

  // Fungsi untuk memperbarui status notifikasi (misal: mark as read/unread)
  Future<void> _updateNotifikasiStatus(bool newStatus) async {
    setState(() {
      _isLoading = true;
    });

    final String? authToken = await _getAuthToken();
    if (authToken == null) {
      showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
      setState(() => _isLoading = false);
      return;
    }

    try {
      print('Updating notification status for ID: ${widget.notifikasi.id}');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken, Accept: application/json}',
      );
      print('Body being sent: ${jsonEncode({'dibaca': newStatus})}');

      final response = await http.put(
        Uri.parse('$_baseUrl/notifikasis/${widget.notifikasi.id}'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
          'Accept': 'application/json',
        },
        body: jsonEncode({'dibaca': newStatus}),
      );

      print('Response status for update notification: ${response.statusCode}');
      print('Response body for update notification: ${response.body}');

      if (response.statusCode == 200) {
        setState(() {
          _dibacaStatus = newStatus; // Update state lokal
        });
        showSnackBar('Status notifikasi berhasil diperbarui.');
        Navigator.pop(context, true); // Kembali dan beri sinyal untuk refresh
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memperbarui notifikasi. Silakan login ulang.',
        );
      } else {
        final responseData = json.decode(response.body);
        showSnackBar(
          'Gagal memperbarui notifikasi: ${responseData['message'] ?? 'Terjadi kesalahan'}',
        );
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat memperbarui notifikasi: $error');
      print('Error during _updateNotifikasiStatus: $error');
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Detail Notifikasi')),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: Card(
                elevation: 4,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(20.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        'Pesan Notifikasi:',
                        style: Theme.of(context).textTheme.titleLarge?.copyWith(
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 8.0),
                      Text(
                        widget.notifikasi.pesan,
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                      const Divider(height: 32, thickness: 1),
                      Row(
                        children: [
                          const Icon(
                            Icons.person,
                            color: Colors.grey,
                            size: 20,
                          ),
                          const SizedBox(width: 8),
                          Text(
                            'Dari: ${widget.notifikasi.user?.nama ?? 'Sistem'}',
                            style: const TextStyle(
                              fontSize: 16,
                              color: Colors.grey,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 8.0),
                      Row(
                        children: [
                          const Icon(
                            Icons.access_time,
                            color: Colors.grey,
                            size: 20,
                          ),
                          const SizedBox(width: 8),
                          Text(
                            widget.notifikasi.createdAt != null
                                ? 'Waktu: ${DateFormat('dd MMM yyyy, HH:mm').format(widget.notifikasi.createdAt!)}'
                                : 'Waktu tidak tersedia',
                            style: const TextStyle(
                              fontSize: 16,
                              color: Colors.grey,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16.0),
                      Row(
                        children: [
                          Icon(
                            _dibacaStatus
                                ? Icons.check_circle
                                : Icons.radio_button_unchecked,
                            color: _dibacaStatus ? Colors.green : Colors.red,
                            size: 24,
                          ),
                          const SizedBox(width: 8),
                          Text(
                            _dibacaStatus
                                ? 'Status: Sudah Dibaca'
                                : 'Status: Belum Dibaca',
                            style: TextStyle(
                              color: _dibacaStatus ? Colors.green : Colors.red,
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 24.0),
                      // Tombol untuk mengubah status dibaca
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton.icon(
                          onPressed: () {
                            // Toggle status dibaca
                            _updateNotifikasiStatus(!_dibacaStatus);
                          },
                          style: ElevatedButton.styleFrom(
                            padding: const EdgeInsets.symmetric(vertical: 12.0),
                            backgroundColor: _dibacaStatus
                                ? Colors.orange
                                : Colors
                                      .blue, // Warna tombol berdasarkan status
                            foregroundColor: Colors.white,
                          ),
                          icon: Icon(
                            _dibacaStatus
                                ? Icons.mark_email_unread
                                : Icons.mark_email_read,
                          ),
                          label: Text(
                            _dibacaStatus
                                ? 'Tandai Belum Dibaca'
                                : 'Tandai Sudah Dibaca',
                          ),
                        ),
                      ),
                      // Anda bisa menambahkan tombol lain di sini, misalnya untuk menghapus notifikasi
                      // const SizedBox(height: 12.0),
                      // SizedBox(
                      //   width: double.infinity,
                      //   child: ElevatedButton.icon(
                      //     onPressed: () {
                      //       // Implementasi hapus notifikasi
                      //     },
                      //     style: ElevatedButton.styleFrom(
                      //       padding: const EdgeInsets.symmetric(vertical: 12.0),
                      //       backgroundColor: Colors.red,
                      //       foregroundColor: Colors.white,
                      //     ),
                      //     icon: const Icon(Icons.delete),
                      //     label: const Text('Hapus Notifikasi'),
                      //   ),
                      // ),
                    ],
                  ),
                ),
              ),
            ),
    );
  }
}
