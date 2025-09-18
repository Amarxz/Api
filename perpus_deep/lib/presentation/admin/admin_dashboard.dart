import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../data/providers/auth_provider.dart';

// Import semua screen CRUD
import 'buku/buku_list_screen.dart';
import 'kategori/kategori_list_screen.dart';
import 'pemesanan/pemesanan_list_screen.dart';
import 'peminjaman/peminjaman_list_screen.dart';
import 'user/user_list_screen.dart';
import 'notifikasi/notifikasi_list_screen.dart'; // Import NotifikasiScreen

class AdminDashboard extends StatelessWidget {
  const AdminDashboard({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'Dashboard Admin',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.bold,
            fontSize: 22,
          ),
        ),
        centerTitle: true,
        backgroundColor: Colors.deepPurple, // Warna AppBar konsisten
        foregroundColor: Colors.white,
        elevation: 0, // Tanpa bayangan untuk tampilan flat
        iconTheme: const IconThemeData(
          color: Colors.white,
        ), // Untuk tombol kembali jika ada
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () {
              authProvider.logout().then((_) {
                // Pastikan context masih mounted sebelum navigasi
                if (!context.mounted) return;
                Navigator.pushReplacementNamed(context, '/login');
              });
            },
          ),
        ],
      ),
      body: Container(
        // Latar belakang gradien yang fancy
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            colors: [
              Colors.deepPurple,
              Color(
                0xFF8A2BE2,
              ), // Warna gradien yang sama dengan Login/Register
            ],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
        ),
        child: SingleChildScrollView(
          // Tambahkan SingleChildScrollView untuk konten
          padding: const EdgeInsets.all(24.0), // Tambah padding keseluruhan
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Welcome Header yang dipercantik
              Card(
                // Menggunakan Card untuk tampilan modern
                elevation: 10,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(20.0),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(30.0),
                  child: Column(
                    children: [
                      const Icon(
                        Icons.admin_panel_settings,
                        size: 80, // Ukuran ikon lebih besar
                        color: Colors.deepPurple, // Warna ikon konsisten
                      ),
                      const SizedBox(height: 16),
                      Text(
                        'Halo, Admin!', // Pesan sambutan yang lebih personal
                        style: Theme.of(context).textTheme.headlineSmall
                            ?.copyWith(
                              color: Colors.deepPurple.shade800, // Warna teks
                              fontWeight: FontWeight.bold,
                              fontSize: 24, // Ukuran font
                            ),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Kelola perpustakaan digital Anda dengan mudah di sini.', // Pesan deskriptif
                        textAlign: TextAlign.center,
                        style: Theme.of(context).textTheme.bodyMedium?.copyWith(
                          color: Colors.grey.shade700, // Warna teks
                          fontSize: 16,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 32), // Spasi antar bagian
              // Menu Title yang modern
              Text(
                'Menu Pengelolaan',
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: Colors.white, // Warna teks agar kontras dengan gradien
                  fontSize: 22,
                ),
              ),
              const SizedBox(height: 24), // Spasi antar judul dan grid
              // Menu Grid yang fancy
              GridView.count(
                shrinkWrap:
                    true, // Penting untuk digunakan di dalam SingleChildScrollView
                physics:
                    const NeverScrollableScrollPhysics(), // Non-scrollable untuk GridView
                crossAxisCount: 2,
                crossAxisSpacing: 20, // Jarak antar kolom
                mainAxisSpacing: 20, // Jarak antar baris
                childAspectRatio: 1, // Rasio aspek mendekati persegi
                children: [
                  _buildMenuCard(
                    context,
                    title: 'Kelola Kategori',
                    icon: Icons.category,
                    color: Colors.orange, // Warna tetap untuk identitas menu
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const KategoriListScreen(),
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Kelola Buku',
                    icon: Icons.book,
                    color: Colors.green,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const BukuListScreen(),
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Kelola Pemesanan',
                    icon: Icons.shopping_cart,
                    color: Colors.purple,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const PemesananListScreen(),
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Kelola Peminjaman',
                    icon: Icons.library_books,
                    color: Colors.teal,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const PeminjamanListScreen(),
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Kelola User',
                    icon: Icons.people,
                    color: Colors.red,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (context) => const UserListScreen(),
                        ),
                      );
                    },
                  ),
                  _buildMenuCard(
                    context,
                    title: 'Kelola Notifikasi',
                    icon: Icons.notifications,
                    color: Colors.blueGrey,
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
                    title: 'Statistik',
                    icon: Icons.analytics,
                    color: Colors.indigo,
                    onTap: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(
                          content: Text('Fitur statistik akan segera hadir'),
                          backgroundColor:
                              Colors.deepPurpleAccent, // Warna snackbar
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
      elevation: 8, // Tingkatkan elevasi untuk bayangan lebih dalam
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
      ), // Sudut lebih bulat
      clipBehavior: Clip.antiAlias, // Penting untuk gradien di dalam Card
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(16),
            gradient: LinearGradient(
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
              colors: [
                color.withOpacity(0.15),
                color.withOpacity(0.08),
              ], // Gradien lebih menonjol
            ),
          ),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Container(
                padding: const EdgeInsets.all(16), // Padding ikon lebih besar
                decoration: BoxDecoration(
                  color: color.withOpacity(
                    0.3,
                  ), // Latar belakang ikon lebih solid
                  borderRadius: BorderRadius.circular(
                    12,
                  ), // Sudut ikon lebih bulat
                ),
                child: Icon(
                  icon,
                  size: 40,
                  color: color,
                ), // Ukuran ikon lebih besar
              ),
              const SizedBox(height: 16), // Spasi lebih besar
              Text(
                title,
                textAlign: TextAlign.center,
                style: Theme.of(context).textTheme.titleMedium?.copyWith(
                  fontWeight: FontWeight.bold, // Teks lebih tebal
                  color: Colors.deepPurple.shade800, // Warna teks ikon
                  fontSize: 18, // Ukuran font teks ikon
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
