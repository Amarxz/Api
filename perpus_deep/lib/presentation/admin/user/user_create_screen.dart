import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart (kini digunakan)

class UserCreateScreen extends StatefulWidget {
  const UserCreateScreen({super.key});

  @override
  _UserCreateScreenState createState() => _UserCreateScreenState();
}

class _UserCreateScreenState extends State<UserCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  final _namaController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  String _role = 'anggota'; // Default role
  bool _isLoading = false;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  // Hapus _showSnackBar lokal jika ada, karena kita akan menggunakan yang dari utils.dart
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

  Future<void> _createUser() async {
    if (_formKey.currentState!.validate()) {
      setState(() {
        _isLoading = true;
      });
      try {
        print('Creating user at: $_baseUrl/register');
        print(
          'Body being sent: ${jsonEncode({'nama': _namaController.text, 'email': _emailController.text, 'password': _passwordController.text, 'role': _role})}',
        );
        print(
          'Headers being sent: {Content-Type: application/json, Accept: application/json}',
        );

        final response = await http.post(
          Uri.parse(
            '$_baseUrl/register',
          ), // Menggunakan endpoint register untuk admin juga
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json', // Tambahkan header ini
          },
          body: jsonEncode({
            'nama': _namaController.text,
            'email': _emailController.text,
            'password': _passwordController.text,
            'role': _role,
          }),
        );

        print('Response status for create user: ${response.statusCode}');
        print('Response body for create user: ${response.body}');

        if (response.statusCode == 201) {
          showSnackBar(
            'User berhasil ditambahkan.',
          ); // Menggunakan showSnackBar global
          Navigator.pop(context, true); // Kembali ke daftar user dan refresh
        } else {
          final responseData = json.decode(response.body);
          showSnackBar(
            'Gagal menambahkan user: ${responseData['message'] ?? 'Terjadi kesalahan'}', // Menggunakan showSnackBar global
          );
        }
      } catch (error) {
        showSnackBar(
          'Terjadi kesalahan saat menambahkan user: $error',
        ); // Menggunakan showSnackBar global
        print('Error during _createUser: $error');
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
      appBar: AppBar(title: const Text('Tambah User')), // Menambahkan const
      body: Padding(
        padding: const EdgeInsets.all(16.0), // Menambahkan const
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              TextFormField(
                controller: _namaController,
                decoration: const InputDecoration(
                  labelText: 'Nama Lengkap',
                ), // Menambahkan const
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Nama tidak boleh kosong';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0), // Menambahkan const
              TextFormField(
                controller: _emailController,
                decoration: const InputDecoration(
                  labelText: 'Email',
                ), // Menambahkan const
                validator: (value) {
                  if (value == null || value.isEmpty || !value.contains('@')) {
                    return 'Masukkan email yang valid';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0), // Menambahkan const
              TextFormField(
                controller: _passwordController,
                obscureText: true,
                decoration: const InputDecoration(
                  labelText: 'Password',
                ), // Menambahkan const
                validator: (value) {
                  if (value == null || value.isEmpty || value.length < 6) {
                    return 'Password minimal 6 karakter';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16.0), // Menambahkan const
              DropdownButtonFormField<String>(
                decoration: const InputDecoration(
                  labelText: 'Role',
                ), // Menambahkan const
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
              const SizedBox(height: 24.0), // Menambahkan const
              SizedBox(
                width: double.infinity,
                child: _isLoading
                    ? const Center(
                        child: CircularProgressIndicator(),
                      ) // Menambahkan const
                    : ElevatedButton(
                        onPressed: _createUser,
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(
                            vertical: 16.0,
                          ), // Menambahkan const
                        ),
                        child: const Text('Simpan'), // Menambahkan const
                      ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
