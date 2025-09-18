import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:provider/provider.dart'; // Import Provider
import '../../../data/models/user_model.dart'; // Import UserModel untuk dropdown user
import '../../../common/utils.dart'; // Untuk showSnackBar
import '../../../data/providers/auth_provider.dart'; // Untuk mendapatkan token

class NotifikasiCreateScreen extends StatefulWidget {
  const NotifikasiCreateScreen({super.key});

  @override
  _NotifikasiCreateScreenState createState() => _NotifikasiCreateScreenState();
}

class _NotifikasiCreateScreenState extends State<NotifikasiCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _pesanController = TextEditingController();
  int? _selectedUserId; // ID user yang dipilih untuk notifikasi
  List<User> _users = []; // Daftar user untuk dropdown

  bool _isLoading = false; // Status loading untuk proses pengiriman notifikasi
  bool _loadingUsers = true; // Status loading untuk memuat daftar user

  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchUsers(); // Memuat daftar user saat layar diinisialisasi
  }

  @override
  void dispose() {
    _pesanController.dispose();
    super.dispose();
  }

  // Fungsi untuk mendapatkan token dari AuthProvider
  Future<String?> _getAuthToken() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final String? token = authProvider.token;
    print(
      'Auth Token retrieved in NotifikasiCreateScreen: $token',
    ); // Debug print
    return token;
  }

  // Fungsi untuk memuat daftar user dari API
  Future<void> _fetchUsers() async {
    setState(() {
      _loadingUsers = true;
    });
    try {
      final String? authToken = await _getAuthToken();
      if (authToken == null) {
        showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
        setState(() => _loadingUsers = false);
        return;
      }

      print('Fetching users for notification from: $_baseUrl/users');
      print(
        'Headers being sent: {Authorization: Bearer $authToken, Accept: application/json}',
      );

      final response = await http.get(
        Uri.parse('$_baseUrl/users'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
          'Accept': 'application/json',
        },
      );

      print('Response status for users: ${response.statusCode}');
      print('Response body for users: ${response.body}');

      if (response.statusCode == 200) {
        final dynamic decodedResponse = json.decode(response.body);
        List<dynamic> data;

        if (decodedResponse is List) {
          data = decodedResponse; // API mengembalikan list langsung
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data =
              decodedResponse['data']; // API mengembalikan map dengan kunci 'data'
        } else {
          showSnackBar('Format data user tidak sesuai.');
          setState(() => _loadingUsers = false);
          return;
        }

        setState(() {
          _users = data.map((json) => User.fromJson(json)).toList();
          _loadingUsers = false;
        });
        showSnackBar('Daftar user berhasil dimuat.');
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat daftar user. Silakan login ulang.',
        );
        setState(() => _loadingUsers = false);
      } else {
        showSnackBar('Gagal memuat daftar user: ${response.statusCode}');
        setState(() => _loadingUsers = false);
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat memuat daftar user: $error');
      print('Error during _fetchUsers in NotifikasiCreateScreen: $error');
      setState(() => _loadingUsers = false);
    }
  }

  // Fungsi untuk mengirim notifikasi baru
  Future<void> _createNotifikasi() async {
    if (_formKey.currentState!.validate() && _selectedUserId != null) {
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

        print('Creating notification at: $_baseUrl/notifikasis');
        print(
          'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken, Accept: application/json}',
        );
        print(
          'Body being sent: ${jsonEncode({'user_id': _selectedUserId, 'pesan': _pesanController.text})}',
        );

        final response = await http.post(
          Uri.parse('$_baseUrl/notifikasis'),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $authToken',
            'Accept': 'application/json',
          },
          body: jsonEncode({
            'user_id': _selectedUserId,
            'pesan': _pesanController.text,
          }),
        );

        print(
          'Response status for create notification: ${response.statusCode}',
        );
        print('Response body for create notification: ${response.body}');

        if (response.statusCode == 201) {
          showSnackBar('Notifikasi berhasil dikirim!');
          Navigator.pop(
            context,
            true,
          ); // Kembali ke daftar notifikasi dan refresh
        } else if (response.statusCode == 401 || response.statusCode == 403) {
          showSnackBar(
            'Autentikasi gagal saat mengirim notifikasi. Silakan login ulang.',
          );
        } else {
          final responseData = json.decode(response.body);
          showSnackBar(
            'Gagal mengirim notifikasi: ${responseData['message'] ?? 'Terjadi kesalahan'}',
          );
        }
      } catch (error) {
        showSnackBar('Terjadi kesalahan saat mengirim notifikasi: $error');
        print('Error during _createNotifikasi: $error');
      } finally {
        setState(() {
          _isLoading = false;
        });
      }
    } else if (_selectedUserId == null) {
      showSnackBar('User harus dipilih.');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Kirim Notifikasi Baru')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              // Dropdown untuk memilih User
              _loadingUsers
                  ? const Center(child: CircularProgressIndicator())
                  : DropdownButtonFormField<int>(
                      decoration: const InputDecoration(
                        labelText: 'Pilih User Penerima',
                      ),
                      value: _selectedUserId,
                      items: _users.map((User user) {
                        return DropdownMenuItem<int>(
                          value: user.id,
                          child: Text('${user.nama} (${user.email})'),
                        );
                      }).toList(),
                      onChanged: (int? newValue) {
                        setState(() {
                          _selectedUserId = newValue;
                        });
                      },
                      validator: (value) {
                        if (value == null) {
                          return 'User penerima harus dipilih';
                        }
                        return null;
                      },
                    ),
              const SizedBox(height: 16.0),

              // Field Pesan Notifikasi
              TextFormField(
                controller: _pesanController,
                decoration: const InputDecoration(
                  labelText: 'Pesan Notifikasi',
                  border: OutlineInputBorder(),
                  alignLabelWithHint: true,
                ),
                maxLines: 5,
                keyboardType: TextInputType.multiline,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Pesan tidak boleh kosong';
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
                        onPressed: _createNotifikasi,
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 16.0),
                        ),
                        child: const Text('Kirim Notifikasi'),
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
