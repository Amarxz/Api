import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../data/providers/auth_provider.dart';
import 'peminjaman/peminjaman_screen.dart'; // Pastikan path benar
import 'pemesanan/pemesanan_screen.dart'; // Pastikan path benar
import 'notifikasi/notifikasi_screen.dart'; // Pastikan path benar
import 'profil_screen.dart'; // Pastikan path benar
import 'buku/buku_list_screen.dart'; // Menambahkan ini untuk fitur daftar buku

class AnggotaDashboard extends StatelessWidget {
  const AnggotaDashboard({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Dashboard Anggota',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.bold,
            fontSize: 22,
          ),
        ),
        centerTitle: true,
        backgroundColor: Colors.deepPurple, // Warna AppBar konsisten
        foregroundColor: Colors.white,
        elevation: 0, // Tanpa bayangan
        iconTheme: const IconThemeData(color: Colors.white),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () {
              authProvider.logout().then((_) {
                if (!context.mounted) return;
                Navigator.pushReplacementNamed(context, '/login');
              });
            },
          ),
        ],
      ),
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: [
              Colors.deepPurple,
              Color(0xFF8A2BE2), // Warna gradien yang sama
            ],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
        ),
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Welcome Header yang dipercantik
              Card(
                elevation: 10,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(20.0),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(30.0),
                  child: Column(
                    children: [
                      const Icon(
                        Icons.person_outline, // Ikon untuk anggota
                        size: 80,
                        color: Colors.deepPurple,
                      ),
                      const SizedBox(height: 16),
                      Text(
                        'Selamat datang, ${authProvider.user?.nama ?? 'Anggota'}!', // Tampilkan nama pengguna
                        style: Theme.of(context).textTheme.headlineSmall
                            ?.copyWith(
                              color: Colors.deepPurple.shade800,
                              fontWeight: FontWeight.bold,
                              fontSize: 24,
                            ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Akses layanan perpustakaan digital Anda.',
                        textAlign: TextAlign.center,
                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                          color: Colors.grey.shade700,
                          fontSize: 16,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 32),

              Text(
                'Menu Utama',
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                  fontSize: 22,
                ),
              ),
              const SizedBox(height: 24),

              // Menu Grid yang fancy
              GridView.count(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisCount: 2,
                crossAxisSpacing: 20,
                mainAxisSpacing: 20,
                childAspectRatio: 1,
                children: [
                  _buildMenuCard(
                    context,
                    title: 'Daftar Buku',
                    icon: Icons.book,
                    color: Colors.blue,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) =>
                              const BukuListScreen(), // Anggota juga bisa lihat daftar buku
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Peminjaman Buku',
                    icon: Icons.library_add_check,
                    color: Colors.green,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const PeminjamanScreen(),
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Pemesanan Buku',
                    icon: Icons.shopping_bag,
                    color: Colors.orange,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const PemesananScreen(),
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Notifikasi',
                    icon: Icons.notifications_active,
                    color: Colors.redAccent,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const NotifikasiScreen(),
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Profil Saya',
                    icon: Icons.person,
                    color: Colors.teal,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const ProfilScreen(),
                        ),
                      );
                    },
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  // Widget pembangun kartu menu yang dipercantik
  Widget _buildMenuCard(
    BuildContext context, {
    required String title,
    required IconData icon,
    required Color color,
    required VoidCallback onTap,
  }) {
    return Card(
      elevation: 8,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      clipBehavior: Clip.antiAlias,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(16),
            gradient: LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [color.withOpacity(0.15), color.withOpacity(0.08)],
            ),
          ),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: color.withOpacity(0.3),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, size: 40, color: color),
              ),
              const SizedBox(height: 16),
              Text(
                title,
                textAlign: TextAlign.center,
                style: Theme.of(context).textTheme.titleMedium?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: Colors.deepPurple.shade800,
                  fontSize: 18,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
