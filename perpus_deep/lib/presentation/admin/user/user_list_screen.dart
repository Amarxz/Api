import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:provider/provider.dart'; // Import Provider
import '../../../data/models/user_model.dart';
import '../../../common/utils.dart'; // Import showSnackBar dari utils.dart
import '../../../data/providers/auth_provider.dart'; // Import AuthProvider
import 'user_create_screen.dart'; // Import layar create user
import 'user_edit_screen.dart'; // Asumsikan Anda akan membuat UserEditScreen

class UserListScreen extends StatefulWidget {
  const UserListScreen({super.key});

  @override
  _UserListScreenState createState() => _UserListScreenState();
}

class _UserListScreenState extends State<UserListScreen> {
  List<User> _users = [];
  bool _isLoading = true;
  final String _baseUrl = 'http://10.0.2.2:8000/api';

  @override
  void initState() {
    super.initState();
    _fetchUsers();
  }

  // Fungsi untuk mendapatkan token dari AuthProvider
  // Tidak perlu _getAuthToken lokal lagi
  /*
  Future<String?> _getAuthToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');
    print('Auth Token retrieved from SharedPreferences in UserListScreen: $token');
    return token;
  }
  */

  Future<void> _fetchUsers() async {
    setState(() {
      _isLoading = true;
    });
    try {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final String? authToken = authProvider.token;

      if (authToken == null) {
        showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
        setState(() => _isLoading = false);
        return;
      }

      // Debug print: URL dan Header yang dikirim
      print('Fetching users from: $_baseUrl/users');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken}',
      );

      final response = await http.get(
        Uri.parse('$_baseUrl/users'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization':
              'Bearer $authToken', // Tambahkan header Authorization
          'Accept': 'application/json', // Tambahkan Accept header
        },
      );

      // Debug print: Respons dari API
      print('Response status for users: ${response.statusCode}');
      print('Response body for users: ${response.body}');

      if (response.statusCode == 200) {
        // PERBAIKAN: Tangani respons API yang bisa berupa List<dynamic> langsung atau Map<String, dynamic> dengan kunci 'data'
        final dynamic decodedResponse = json.decode(response.body);

        List<dynamic> data;
        if (decodedResponse is List) {
          data = decodedResponse; // API mengembalikan list langsung
          print('API responded with a direct list for users.');
        } else if (decodedResponse is Map &&
            decodedResponse.containsKey('data') &&
            decodedResponse['data'] is List) {
          data =
              decodedResponse['data']; // API mengembalikan map dengan kunci 'data'
          print('API responded with a map containing a "data" key for users.');
        } else {
          showSnackBar('Format data user tidak sesuai.');
          setState(() => _isLoading = false);
          print(
            'Error: Unexpected API response format for users. Response was not a List or a Map with a "data" key.',
          );
          return;
        }

        setState(() {
          _users = data.map((json) => User.fromJson(json)).toList();
          _isLoading = false;
        });
        showSnackBar('Data user berhasil dimuat.'); // Konfirmasi berhasil
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar(
          'Autentikasi gagal saat memuat user. Silakan login ulang.',
        );
        setState(() => _isLoading = false);
        // Opsi: Hapus token dan arahkan ke halaman login
        // final prefs = await SharedPreferences.getInstance();
        // await prefs.remove('token');
        // Navigator.pushReplacementNamed(context, '/login');
      } else {
        showSnackBar('Gagal memuat data user: ${response.statusCode}');
        print(
          'Error: API returned status code ${response.statusCode} for users.',
        );
        setState(() => _isLoading = false);
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan: $error');
      print('Error during _fetchUsers: $error'); // Debug print error
      setState(() => _isLoading = false);
    }
  }

  Future<void> _deleteUser(int id) async {
    try {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final String? authToken = authProvider.token;

      if (authToken == null) {
        showSnackBar('Anda belum login. Silakan login terlebih dahulu.');
        return;
      }

      print('Deleting user from: $_baseUrl/users/$id');
      print(
        'Headers being sent: {Content-Type: application/json, Authorization: Bearer $authToken, Accept: application/json}',
      );

      final response = await http.delete(
        Uri.parse('$_baseUrl/users/$id'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $authToken',
          'Accept': 'application/json',
        },
      );

      print('Delete Response status: ${response.statusCode}');
      print('Delete Response body: ${response.body}');

      if (response.statusCode == 200) {
        showSnackBar('User berhasil dihapus.');
        _fetchUsers(); // Refresh list after deletion
      } else if (response.statusCode == 401 || response.statusCode == 403) {
        showSnackBar('Autentikasi gagal. Silakan login ulang.');
      } else {
        final responseData = json.decode(response.body);
        showSnackBar(
          'Gagal menghapus user: ${responseData['message'] ?? 'Terjadi kesalahan'}',
        );
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat menghapus user: $error');
      print('Error during _deleteUser: $error');
    }
  }

  void _showDeleteConfirmation(User user) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Konfirmasi Hapus'),
          content: Text(
            'Apakah Anda yakin ingin menghapus user "${user.nama}"?',
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Batal'),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
                _deleteUser(user.id);
              },
              child: const Text('Hapus', style: TextStyle(color: Colors.red)),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Kelola User'),
        actions: [
          IconButton(
            icon: const Icon(Icons.person_add),
            onPressed: () async {
              final result = await Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => const UserCreateScreen(),
                ),
              );
              if (result == true) {
                _fetchUsers();
              }
            },
          ),
        ],
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : _users.isEmpty
          ? const Center(child: Text('Tidak ada user.'))
          : RefreshIndicator(
              onRefresh: _fetchUsers,
              child: ListView.builder(
                itemCount: _users.length,
                itemBuilder: (context, index) {
                  final user = _users[index];
                  return Card(
                    margin: const EdgeInsets.all(8.0),
                    child: ListTile(
                      title: Text(
                        'Nama: ${user.nama}',
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      subtitle: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Email: ${user.email}'),
                          Text('Role: ${user.role}'),
                          if (user.telepon != null &&
                              user
                                  .telepon!
                                  .isNotEmpty) // Tampilkan telepon jika ada
                            Text('Telepon: ${user.telepon}'),
                        ],
                      ),
                      trailing: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          IconButton(
                            icon: const Icon(Icons.edit, color: Colors.blue),
                            onPressed: () async {
                              // Asumsikan ada UserEditScreen
                              final result = await Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (context) =>
                                      UserEditScreen(user: user),
                                ),
                              );
                              if (result == true) {
                                _fetchUsers();
                              }
                            },
                          ),
                          IconButton(
                            icon: const Icon(Icons.delete, color: Colors.red),
                            onPressed: () => _showDeleteConfirmation(user),
                          ),
                        ],
                      ),
                      onTap: () async {
                        // Tap card juga bisa navigasi ke edit
                        final result = await Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (context) => UserEditScreen(user: user),
                          ),
                        );
                        if (result == true) {
                          _fetchUsers();
                        }
                      },
                    ),
                  );
                },
              ),
            ),
    );
  }
}
