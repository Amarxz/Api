import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'data/providers/auth_provider.dart';
import 'presentation/auth/login_screen.dart';
import 'presentation/admin/admin_dashboard.dart';
import 'presentation/anggota/anggota_dashboard.dart';
import 'widgets/loading_indicator.dart';
import 'common/utils.dart'; // Import scaffoldMessengerKey dari utils.dart

// Hapus definisi duplikat di sini.
// final GlobalKey<ScaffoldMessengerState> scaffoldMessengerKey =
//     GlobalKey<ScaffoldMessengerState>();

void main() {
  runApp(
    MultiProvider(
      providers: [ChangeNotifierProvider(create: (_) => AuthProvider())],
      child: const MyApp(), // Menambahkan const
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Perpus Deep',
      scaffoldMessengerKey:
          scaffoldMessengerKey, // Menggunakan key yang diimpor dari utils.dart
      theme: ThemeData(
        primarySwatch: Colors.indigo,
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
      home: const AuthCheck(), // Menambahkan const
      routes: {
        '/login': (context) => const LoginScreen(), // Menambahkan const
        '/admin_dashboard': (context) =>
            const AdminDashboard(), // Menambahkan const
        '/anggota_dashboard': (context) =>
            const AnggotaDashboard(), // Menambahkan const
      },
    );
  }
}

class AuthCheck extends StatelessWidget {
  const AuthCheck({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    return FutureBuilder(
      future: authProvider.loadToken(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const LoadingIndicator(); // Menambahkan const
        } else {
          if (authProvider.isLoggedIn) {
            return authProvider.user!.role == 'admin'
                ? const AdminDashboard() // Menambahkan const
                : const AnggotaDashboard(); // Menambahkan const
          } else {
            return const LoginScreen(); // Menambahkan const
          }
        }
      },
    );
  }
}
