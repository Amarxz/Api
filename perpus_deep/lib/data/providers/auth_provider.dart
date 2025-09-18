import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user_model.dart'; // Pastikan UserModel memiliki toJson()
import '../../common/utils.dart'; // Untuk showSnackBar misalnya

class AuthProvider with ChangeNotifier {
  User? _user;
  String? _token;

  User? get user => _user;
  String? get token => _token;
  bool get isLoggedIn => _token != null;

  final String _baseUrl =
      'http://10.0.2.2:8000/api'; // GANTI sesuai IP backend Laravel kamu

  /// Metode baru untuk memperbarui data user secara lokal dan di SharedPreferences
  void setUser(User newUser) {
    _user = newUser;
    SharedPreferences.getInstance().then((prefs) {
      prefs.setString(
        'user',
        json.encode(newUser.toJson()),
      ); // Simpan user terbaru ke SharedPreferences
    });
    notifyListeners();
  }

  /// Login
  Future<bool> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/login'),
        headers: {'Accept': 'application/json'},
        body: {'email': email, 'password': password},
      );

      final responseData = json.decode(response.body);

      if (response.statusCode == 200 && responseData['token'] != null) {
        _token = responseData['token'];
        _user = User.fromJson(
          responseData['user'],
        ); // Inisialisasi _user dari respons

        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('token', _token!);
        await prefs.setString(
          'user',
          json.encode(responseData['user']),
        ); // Simpan data user lengkap

        notifyListeners();
        return true;
      } else {
        showSnackBar(
          'Login gagal: ${responseData['message'] ?? 'Email atau password salah'}',
        );
        return false;
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat login: $error');
      return false;
    }
  }

  /// Register
  Future<bool> register(String nama, String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$_baseUrl/register'),
        headers: {'Accept': 'application/json'},
        body: {'nama': nama, 'email': email, 'password': password},
      );

      final responseData = json.decode(response.body);

      if (response.statusCode == 201) {
        // Setelah registrasi berhasil, jika API mengembalikan user dan token
        if (responseData['token'] != null && responseData['user'] != null) {
          _token = responseData['token'];
          _user = User.fromJson(responseData['user']);
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString('token', _token!);
          await prefs.setString('user', json.encode(responseData['user']));
          notifyListeners();
          showSnackBar('Registrasi berhasil. Anda otomatis login.');
          return true;
        } else {
          showSnackBar(
            'Registrasi berhasil. Silakan login.',
          ); // Jika API tidak otomatis login
          return true;
        }
      } else {
        showSnackBar(
          'Registrasi gagal: ${responseData['message'] ?? 'Terjadi kesalahan'}',
        );
        return false;
      }
    } catch (error) {
      showSnackBar('Terjadi kesalahan saat registrasi: $error');
      return false;
    }
  }

  /// Logout
  Future<void> logout() async {
    try {
      if (_token != null) {
        final response = await http.post(
          Uri.parse('$_baseUrl/logout'),
          headers: {
            'Authorization': 'Bearer $_token',
            'Accept': 'application/json',
          },
        );

        if (response.statusCode == 200) {
          await _clearAuthData();
          showSnackBar('Berhasil logout.');
        } else {
          showSnackBar('Gagal logout. Coba lagi.');
        }
      } else {
        showSnackBar('Anda sudah logout.');
      }
    } catch (error) {
      showSnackBar('Kesalahan saat logout: $error');
    }
  }

  /// Load token & user saat startup
  Future<void> loadToken() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('token');
    final userJson = prefs.getString('user');

    if (_token != null && userJson != null) {
      _user = User.fromJson(json.decode(userJson));
    }

    notifyListeners();
  }

  /// Hapus data login
  Future<void> _clearAuthData() async {
    _token = null;
    _user = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
    await prefs.remove('user');
    notifyListeners();
  }
}
