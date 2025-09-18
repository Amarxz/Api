import 'package:flutter/material.dart';
import 'package:http/http.dart' as http; // Import http
import 'dart:convert'; // Import convert
import 'package:provider/provider.dart';
import '../../data/providers/auth_provider.dart';
import '../../common/utils.dart'; // For showSnackBar
import '../../data/models/user_model.dart'; // PERBAIKAN: Import User model
// import 'dart:async'; // Dihapus karena tidak digunakan

class ProfilScreen extends StatefulWidget {
  const ProfilScreen({super.key});

  @override
  _ProfilScreenState createState() => _ProfilScreenState();
}

class _ProfilScreenState extends State<ProfilScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _namaController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _teleponController = TextEditingController();
  final TextEditingController _newPasswordController =
      TextEditingController(); // Controller untuk password baru
  final TextEditingController _confirmNewPasswordController =
      TextEditingController(); // Controller untuk konfirmasi password

  bool _isLoading = false;
  final String _baseUrl = 'http://10.0.2.2:8000/api'; // Base URL API Laravel

  @override
  void initState() {
    super.initState();
    _loadUserProfile(); // Memuat data user saat inisialisasi
  }

  // Fungsi untuk memuat data profil user
  void _loadUserProfile() {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    if (authProvider.user != null) {
      _namaController.text = authProvider.user!.nama;
      _emailController.text = authProvider.user!.email;
      _teleponController.text = authProvider.user!.telepon ?? '';
    }
  }

  @override
  void dispose() {
    _namaController.dispose();
    _emailController.dispose();
    _teleponController.dispose();
    _newPasswordController.dispose(); // Dispose new password controller
    _confirmNewPasswordController
        .dispose(); // Dispose confirm password controller
    super.dispose();
  }

  Future<void> _updateProfil() async {
    if (_formKey.currentState!.validate()) {
      setState(() {
        _isLoading = true;
      });
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final token = authProvider.token;
      final user = authProvider.user;

      if (token != null && user != null) {
        try {
          Map<String, dynamic> requestBody = {
            'nama': _namaController.text,
            // Email tidak perlu dikirim jika disabled, kecuali API mengharapkannya secara eksplisit.
            // Jika API mengharapkannya, pastikan validasi backend mengizinkan email yang sama untuk user yang sama.
            // 'email': _emailController.text,
            'telepon': _teleponController.text.isEmpty
                ? null
                : _teleponController.text,
            // 'role': user.role, // Role biasanya tidak diupdate dari sini, tapi jika perlu, tambahkan
          };

          // Tambahkan password ke body hanya jika diisi
          if (_newPasswordController.text.isNotEmpty) {
            requestBody['password'] = _newPasswordController.text;
          }

          print('Updating profile at: $_baseUrl/users/${user.id}');
          print(
            'Headers being sent: {Authorization: Bearer $token, Accept: application/json, Content-Type: application/json}',
          );
          print('Body being sent: ${jsonEncode(requestBody)}');

          final response = await http.put(
            Uri.parse('$_baseUrl/users/${user.id}'),
            headers: {
              'Authorization': 'Bearer $token',
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            body: jsonEncode(requestBody),
          );

          print('Response status for profile update: ${response.statusCode}');
          print('Response body for profile update: ${response.body}');

          if (response.statusCode == 200) {
            final responseData = json.decode(response.body)['data'];
            // PERBAIKAN: Gunakan setUser untuk memperbarui objek user di AuthProvider
            authProvider.setUser(User.fromJson(responseData));

            showSnackBar('Profil berhasil diperbarui.');

            // Bersihkan bidang password setelah berhasil update
            _newPasswordController.clear();
            _confirmNewPasswordController.clear();
          } else if (response.statusCode == 401 || response.statusCode == 403) {
            showSnackBar('Autentikasi gagal. Silakan login ulang.');
          } else {
            final responseData = json.decode(response.body);
            showSnackBar(
              'Gagal memperbarui profil: ${responseData['message'] ?? 'Terjadi kesalahan'}',
            );
          }
        } catch (error) {
          showSnackBar('Terjadi kesalahan saat memperbarui profil: $error');
          print('Error during _updateProfil: $error');
        } finally {
          setState(() {
            _isLoading = false;
          });
        }
      } else {
        showSnackBar('Anda belum login.');
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final user = authProvider.user;

    return Scaffold(
      appBar: AppBar(title: const Text('Profil Saya')),
      body: user == null
          ? const Center(child: Text('Gagal memuat informasi profil.'))
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16.0),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: <Widget>[
                    TextFormField(
                      controller: _namaController,
                      decoration: const InputDecoration(
                        labelText: 'Nama Lengkap',
                      ),
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
                      enabled: false, // Email tidak bisa diubah dari sini
                    ),
                    const SizedBox(height: 16.0),
                    TextFormField(
                      controller: _teleponController,
                      decoration: const InputDecoration(
                        labelText: 'Nomor Telepon (opsional)',
                      ),
                    ),
                    const SizedBox(height: 16.0),
                    // Bidang untuk mengubah password
                    TextFormField(
                      controller: _newPasswordController,
                      obscureText: true,
                      decoration: const InputDecoration(
                        labelText:
                            'Password Baru (kosongkan jika tidak ingin mengubah)',
                      ),
                      validator: (value) {
                        if (value != null && value.isNotEmpty) {
                          if (value.length < 6) {
                            return 'Password minimal 6 karakter';
                          }
                          if (value != _confirmNewPasswordController.text) {
                            return 'Password baru dan konfirmasi tidak cocok';
                          }
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16.0),
                    TextFormField(
                      controller: _confirmNewPasswordController,
                      obscureText: true,
                      decoration: const InputDecoration(
                        labelText: 'Konfirmasi Password Baru',
                      ),
                      validator: (value) {
                        if (_newPasswordController.text.isNotEmpty &&
                            (value == null || value.isEmpty)) {
                          return 'Konfirmasi password tidak boleh kosong';
                        }
                        if (value != _newPasswordController.text) {
                          return 'Konfirmasi password tidak cocok';
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
                              onPressed: _updateProfil,
                              style: ElevatedButton.styleFrom(
                                padding: const EdgeInsets.symmetric(
                                  vertical: 16.0,
                                ),
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
