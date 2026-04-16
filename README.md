# KlikSimpus.com x HEARTS360 Integration Bridge

Repository ini berfungsi sebagai **Bridge (Jembatan)** data antara EHR KlikSimpus.com dengan Dashboard Longitudinal HEARTS360 (WHO Toolkit).

## 📂 Pembagian Repository & Infrastruktur
Proyek ini terbagi menjadi tiga komponen utama untuk memastikan skalabilitas dan keamanan:

1.  **EHR KlikSimpus.com (Internal)**: Sumber data utama (Database).
2.  **Integration Bridge (Repository ini)**: Unit yang melakukan ekstraksi, translasi, dan pengiriman data sFTP.
3.  **HEARTS360 Toolkit (Dashboard)**: Mesin visualisasi data berbasis Docker (Grafana & Postgres).

## 🔄 Alur Transaksi Data (Data Pipeline)
1.  **Extraction**: Script menarik data pasien Hipertensi/Diabetes, TTV, dan Jadwal Kontrol dari Database KlikSimpus.com.
2.  **Translation (Skrip Translasi)**: Data diubah dari format database internal menjadi format standar **CSV Longitudinal WHO**.
3.  **Transmission**: File CSV dikirimkan ke server sFTP Dashboard melalui jalur terenkripsi (Port 22/sFTP).
4.  **Ingestion**: Dashboard HEARTS360 otomatis menyerap data CSV tersebut untuk ditampilkan di grafik.

## 🛠️ Cara Penggunaan Skrip Translasi
Jalankan perintah berikut untuk memulai sinkronisasi:
```bash
php artisan hearts360:sync
```

Opsi tambahan:
*   `--months=6`: Menarik data hanya untuk 6 bulan terakhir.
*   `--dry-run`: Hanya melakukan ekspor lokal tanpa mengirim ke sFTP (untuk testing).

## ⚙️ Persyaratan Sistem
*   PHP 8.1+
*   Laravel 10.x
*   Composer dependensi: `league/flysystem-sftp-v3`
*   Akses Network: Port 22/sFTP terbuka menuju IP Dashboard HEARTS360.

---
*Dokumentasi ini disiapkan untuk keperluan Technical Submission Hackathon HEARTS360.*
