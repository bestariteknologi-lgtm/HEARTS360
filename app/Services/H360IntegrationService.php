<?php

namespace App\Services;

use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\PendaftaranDiagnosa;
use Illuminate\Support\Facades\DB;

class H360IntegrationService
{
    /**
     * Mengambil data pasien yang memenuhi kriteria Hipertensi/Diabetes
     * sesuai standar HEARTS360 (WHO)
     */
    public function getLongitudinalData($monthLimit = 12)
    {
        // Kode ICD-10 Standar:
        // C00-C99: Cancer (Lainnya)
        // I10-I15: Hypertension
        // E10-E14: Diabetes
        
        $targetDate = now()->subMonths($monthLimit);

        return PendaftaranDiagnosa::select(
                'pasien.id as patient_id',
                'pasien.nama as full_name',
                'pasien.nomor_hp as phone_number',
                'pasien.alamat as address',
                'pasien.jenis_kelamin as gender',
                'pasien.tanggal_lahir as date_of_birth',
                'pendaftaran.id as encounter_id',
                'pendaftaran.created_at as encounter_date',
                'pendaftaran.sistole',
                'pendaftaran.diastole',
                'pendaftaran.gula_darah_sewaktu',
                'pendaftaran_diagnosa.kode_icd',
                'pendaftaran_diagnosa.nama_diagnosa',
                'janji_ketemu_dokter.waktu as next_appointment'
            )
            ->join('pendaftaran', 'pendaftaran_diagnosa.pendaftaran_id', '=', 'pendaftaran.id')
            ->join('pasien', 'pendaftaran.pasien_id', '=', 'pasien.id')
            ->leftJoin('janji_ketemu_dokter', function($join) {
                $join->on('pasien.id', '=', 'janji_ketemu_dokter.pasien_id')
                     ->where('janji_ketemu_dokter.waktu', '>', now())
                     ->whereRaw('janji_ketemu_dokter.waktu = (select min(waktu) from janji_ketemu_dokter where pasien_id = pasien.id and waktu > NOW())');
            })
            ->where(function($query) {
                $query->where('pendaftaran_diagnosa.kode_icd', 'LIKE', 'I10%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'I11%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'I12%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'I13%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'I15%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'E10%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'E11%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'E12%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'E13%')
                      ->orWhere('pendaftaran_diagnosa.kode_icd', 'LIKE', 'E14%');
            })
            ->where('pendaftaran.created_at', '>=', $targetDate)
            ->whereNull('pendaftaran.deleted_at')
            ->whereNull('pasien.deleted_at')
            ->orderBy('pendaftaran.created_at', 'DESC')
            ->get();
    }

    /**
     * Format records into CSV string
     */
    public function formatToCsv($records)
    {
        $header = ['external_id', 'full_name', 'phone', 'gender', 'birthdate', 'visit_date', 'systolic', 'diastolic', 'blood_sugar', 'icd_code', 'next_appointment'];
        
        $csvContent = implode(',', $header) . "\n";

        foreach ($records as $item) {
            $row = [
                $item->patient_id,
                '"' . str_replace('"', '""', $item->full_name) . '"',
                $item->phone_number,
                $item->gender == 'L' ? 'male' : 'female',
                $item->date_of_birth,
                \Carbon\Carbon::parse($item->encounter_date)->format('Y-m-d'),
                $item->sistole,
                $item->diastole,
                $item->gula_darah_sewaktu,
                $item->kode_icd,
                $item->next_appointment ? \Carbon\Carbon::parse($item->next_appointment)->format('Y-m-d') : ''
            ];
            $csvContent .= implode(',', $row) . "\n";
        }

        return $csvContent;
    }

    /**
     * Format data ke format JSON yang siap dikirim via sFTP
     */
    public function formatToJson($data)
    {
        return $data->map(function($item) {
            return [
                'external_id' => $item->patient_id,
                'full_name' => $item->full_name,
                'phone' => $item->phone_number,
                'gender' => $item->gender == 'L' ? 'male' : 'female',
                'birthdate' => $item->date_of_birth,
                'visit_date' => \Carbon\Carbon::parse($item->encounter_date)->format('Y-m-d'),
                'systolic' => $item->sistole,
                'diastolic' => $item->diastole,
                'blood_sugar' => $item->gula_darah_sewaktu,
                'icd_code' => $item->kode_icd,
                'next_appointment' => $item->next_appointment ? \Carbon\Carbon::parse($item->next_appointment)->format('Y-m-d') : null,
            ];
        });
    }
}
