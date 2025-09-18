import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:provider/provider.dart'; // Import Provider
import '../../../data/models/user_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart (kini digunakan)
import '../../../data/providers/auth_provider.dart'; // Import AuthProvider

class UserEditScreen extends StatefulWidget {
  final User user;

  const UserEditScreen({super.key, required this.user});

  @override
  _UserEditScreenState createState() => _UserEditScreenState();
}

class _UserEditScreenState extends State<UserEditScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _namaController;
  late TextEditingController _emailController;
  late TextEditingController _teleponController;
  final TextEditingController _passwordController =
      TextEditingController(); // Controller untuk password baru
  late String _role;
  bool _isLoading = false;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _namaController = TextEditingController(text: widget.user.nama);
    _emailController = TextEditingController(text: widget.user.email);
    _teleponController = TextEditingController(text: widget.user.telepon ?? '');
    _role = widget.user.role;
  }

  @override
  void dispose() {
    _namaController.dispose();
    _emailController.dispose();
    _teleponController.dispose();
    _passwordController.dispose(); // Dispose password controller
    super.dispose();
  }

  Future<void> _updateUser() async {
    if (_formKey.currentState!.validate()) {
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

        Map<String, dynamic> requestBody = {
          'nama': _namaController.text,
          'email': _emailController.text,
          'telepon': _teleponController.text.isEmpty
              ? null
              : _teleponController.text,
          'role': _role,
        };

        // Tambahkan password ke body hanya jika diisi
        if (_passwordController.text.isNotEmpty) {
          requestBody['password'] = _passwordController.text;
        }

        print('Updating user at: $_baseUrl/users/${widget.user.id}');
        print(
          'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken, Accept: application/json}',
        );
        print('Body being sent: ${jsonEncode(requestBody)}');

        final response = await http.put(
          Uri.parse('$_baseUrl/users/${widget.user.id}'),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer $authToken',
            'Accept': 'application/json',
          },
          body: jsonEncode(requestBody),
        );

        print('Response status for update user: ${response.statusCode}');
        print('Response body for update user: ${response.body}');

        if (response.statusCode == 200) {
          showSnackBar('User berhasil diperbarui.');
          Navigator.pop(context, true); // Kembali ke daftar user dan refresh
        } else if (response.statusCode == 401 || response.statusCode == 403) {
          showSnackBar('Autentikasi gagal. Silakan login ulang.');
        } else {
          final responseData = json.decode(response.body);
          showSnackBar(
            'Gagal memperbarui user: ${responseData['message'] ?? 'Terjadi kesalahan'}',
          );
        }
      } catch (error) {
        showSnackBar('Terjadi kesalahan saat memperbarui user: $error');
        print('Error during _updateUser: $error');
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
      appBar: AppBar(title: const Text('Edit User')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              TextFormField(
                controller: _namaController,
                decoration: const InputDecoration(labelText: 'Nama Lengkap'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Nama tidak boleh kosong';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0),
              TextFormField(
                controller: _emailController,
                decoration: const InputDecoration(labelText: 'Email'),
                // Email kini bisa diubah
                validator: (value) {
                  if (value == null || value.isEmpty || !value.contains('@')) {
                    return 'Masukkan email yang valid';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0),
              TextFormField(
                controller: _teleponController,
                decoration: const InputDecoration(
                  labelText: 'Nomor Telepon (opsional)',
                ),
              ),
              const SizedBox(height: 16.0),
              TextFormField(
                controller: _passwordController,
                obscureText: true,
                decoration: const InputDecoration(
                  labelText:
                      'Password Baru (kosongkan jika tidak ingin mengubah)',
                ),
                validator: (value) {
                  if (value != null && value.isNotEmpty && value.length < 6) {
                    return 'Password minimal 6 karakter';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0),
              DropdownButtonFormField<String>(
                decoration: const InputDecoration(labelText: 'Role'),
                value: _role,
                items: <String>['admin', 'anggota'].map((String value) {
                  return DropdownMenuItem<String>(
                    value: value,
                    child: Text(value),
                  );
                }).toList(),
                onChanged: (String? newValue) {
                  setState(() {
                    _role = newValue!;
                  });
                },
              ),
              const SizedBox(height: 24.0),
              SizedBox(
                width: double.infinity,
                child: _isLoading
                    ? const Center(child: CircularProgressIndicator())
                    : ElevatedButton(
                        onPressed: _updateUser,
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
