<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSampleSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'title' => 'Sistem Informasi Akademik Kampus',
                'description' => 'Aplikasi pengelolaan data mahasiswa, dosen, kartu rencana studi (KRS), dan rekapitulasi nilai berbasis web.',
                'status' => 'published',
            ],
            [
                'title' => 'E-Commerce UMKM Batik Tradisional',
                'description' => 'Platform toko online untuk mempromosikan dan menjual produk batik lokal dengan fitur keranjang belanja dan checkout.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Manajemen Inventaris Gudang',
                'description' => 'Sistem pencatatan barang masuk dan keluar, monitoring stok minimum, serta pembuatan laporan mutasi barang.',
                'status' => 'published',
            ],
            [
                'title' => 'Portal Berita Mahasiswa Terkini',
                'description' => 'Website publikasi artikel, warta kampus, opini mahasiswa, dan liputan kegiatan UKM secara berkala.',
                'status' => 'published',
            ],
            [
                'title' => 'Sistem Pemesanan Tiket Bioskop Online',
                'description' => 'Aplikasi reservasi kursi studio, pemilihan jadwal tayang film, dan konfirmasi bukti tiket elektronik.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Kasir Point of Sale Restoran',
                'description' => 'Perangkat lunak kasir restoran untuk mengelola meja, transaksi pembayaran, diskon, dan cetak struk kasir.',
                'status' => 'published',
            ],
            [
                'title' => 'Dashboard Monitoring Kualitas Udara IoT',
                'description' => 'Visualisasi data sensor polusi udara secara real-time menggunakan grafik interaktif dan notifikasi ambang batas.',
                'status' => 'published',
            ],
            [
                'title' => 'Platform Kursus Online Web Development',
                'description' => 'Sistem manajemen pembelajaran (LMS) dengan video tutorial, kuis interaktif, dan tracking progres belajar.',
                'status' => 'published',
            ],
            [
                'title' => 'Sistem Pengarsipan Surat Digital Kantor',
                'description' => 'Aplikasi tata kelola surat masuk dan keluar lengkap dengan nomor otomatis dan unggah berkas PDF.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Pelacakan Pengeluaran Harian',
                'description' => 'Alat bantu finansial pribadi untuk mencatat pengeluaran harian, anggaran bulanan, serta visualisasi kategori.',
                'status' => 'published',
            ],
            [
                'title' => 'Sistem Rekomendasi Tempat Wisata Daerah',
                'description' => 'Platform panduan wisata berbasis lokasi dengan ulasan pengunjung, peta rute, dan rekomendasi kuliner.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Booking Servis Kendaraan Bermotor',
                'description' => 'Layanan pendaftaran antrean bengkel secara daring dengan estimasi biaya dan riwayat perawatan kendaraan.',
                'status' => 'published',
            ],
            [
                'title' => 'Sistem Informasi Manajemen Rumah Sakit',
                'description' => 'Modul pendaftaran pasien rawat jalan, jadwal dokter spesialis, dan riwayat rekam medis sederhana.',
                'status' => 'published',
            ],
            [
                'title' => 'Portal Lowongan Magang Mahasiswa',
                'description' => 'Website penyedia informasi lowongan magang kerja sama kampus, pengunggahan resume, dan pelacakan lamaran.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Pengingat Jadwal Kuliah dan Tugas',
                'description' => 'Kalender digital perkuliahan dengan fitur pengingat tenggat tugas, alarm kelas, dan catatan terintegrasi.',
                'status' => 'published',
            ],
            [
                'title' => 'Sistem Lelang Barang Antik Daring',
                'description' => 'Platform lelang interaktif dengan sistem penawaran harga otomatis dan batasan waktu lelang terverifikasi.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Absensi Karyawan Berbasis Geolocation',
                'description' => 'Sistem presensi kehadiran pegawai menggunakan koordinat GPS dan validasi swafoto secara daring.',
                'status' => 'published',
            ],
            [
                'title' => 'Platform Forum Diskusi Tanya Jawab Teknis',
                'description' => 'Komunitas tanya jawab seputar pemrograman, algoritma komputasi, dan solusi kendala teknis software.',
                'status' => 'published',
            ],
            [
                'title' => 'Sistem Manajemen Perpustakaan Digital',
                'description' => 'Aplikasi katalog buku digital, peminjaman online, pengingat jadwal pengembalian, dan pencatatan denda.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Pemantau Nutrisi dan Pola Makan',
                'description' => 'Kalkulator kebutuhan kalori harian, pencatatan menu makanan bergizi, dan pemantauan berat badan berkala.',
                'status' => 'published',
            ],
            [
                'title' => 'Sistem Manajemen Pengaduan Fasilitas Publik',
                'description' => 'Portal aspirasi dan keluhan warga terkait fasilitas umum dengan nomor tiket pelacakan progres penanganan.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Rental Sewa Mobil dan Motor',
                'description' => 'Platform penyewaan kendaraan dengan pemilihan tanggal sewa, konfirmasi ketersediaan unit, dan invoice.',
                'status' => 'published',
            ],
            [
                'title' => 'Sistem Voting Pemilihan Ketua Himpunan',
                'description' => 'Aplikasi e-voting daring dengan verifikasi token pemilih untuk transparansi pemilihan organisasi kampus.',
                'status' => 'published',
            ],
            [
                'title' => 'Bot Notifikasi Tugas Otomatis Telegram',
                'description' => 'Layanan pengingat tugas kuliah dan jadwal praktikum yang terhubung otomatis ke grup Telegram kelas.',
                'status' => 'published',
            ],
            [
                'title' => 'Aplikasi Manajemen Proyek Tim Mahasiswa',
                'description' => 'Papan Kanban digital untuk pembagian tugas anggota, timeline pengerjaan, dan pemantauan progres kelompok.',
                'status' => 'published',
            ],
        ];

        foreach ($samples as $sample) {
            Project::create($sample);
        }
    }
}
