# Logika Translasi Data (Medical Data Mapping)

Dokumen ini menjelaskan bagaimana data dari KlikSimpus.com diterjemahkan ke dalam standar HEARTS360.

## 1. Pemetaan Field Dasar
| Field HEARTS360 | Database KlikSimpus | Logika / Transformasi |
|---|---|---|
| `Patient ID` | `pasien.id` | Mapping unik berbasis Primary Key internal. |
| `Full Name` | `pasien.nama` | String Trim & Proper Case. |
| `Phone` | `pasien.nomor_telpon` | Standarisasi format International (62...). |
| `Visit Date` | `pendaftaran.tgl_pendaftaran` | Format ISO: `YYYY-MM-DD`. |

## 2. Klasifikasi Klinis (HT/DM)
Skrip translasi melakukan kalkulasi otomatis pada field berikut:

*   **Systolic & Diastolic**: Diambil dari tabel TTV terbaru pada kunjungan tersebut.
*   **Status Kontrol**:
    *   `TERKONTROL`: Jika Sistole < 140 DAN Diastole < 90.
    *   `TIDAK TERKONTROL`: Jika Sistole ≥ 140 ATAU Diastole ≥ 90.
*   **Identifikasi Pasien**:
    *   Filter menggunakan kode ICD-10 pada tabel diagnosa:
        *   **Hipertensi**: `I10` - `I15`.
        *   **Diabetes**: `E10` - `E14`.

## 3. Deteksi Pasien Overdue
Logika ini digunakan untuk "Recall" pasien yang terlambat kontrol:
*   Mencari data pada tabel `janji_ketemu_dokter`.
*   Jika `tgl_janji` < `current_date` DAN belum ada kunjungan baru setelah tanggal tersebut, maka status ditandai sebagai **Overdue**.

## 4. Ekspor Standar
Translasi diakhiri dengan konversi `Array Object` menjadi `Comma Separated Values (CSV)` dengan delimiter `,` dan proteksi string menggunakan `double quotes`.
