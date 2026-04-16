<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class JanjiKetemuDokter extends Model
{
    use Uuids;

    protected $table = "janji_ketemu_dokter";

    protected $fillable = ['dokter_id','poliklinik_id','perusahaan_asuransi_id','jadwal_praktek_id','satusehat_apointment_id','nama_pasien','alamat','nomor_whatapps','waktu','keterangan','pasien_id','setting_id', 'status', 'pendaftaran_id', 'nomor_antrian_id'];

    public function pasien()
    {
        return $this->belongsTo('\App\Models\Pasien');
    }

    public function dokter()
    {
        return $this->belongsTo('\App\Models\User');
    }

    public function poliklinik()
    {
        return $this->belongsTo('\App\Models\Poliklinik', 'poliklinik_id', 'id');
    }

    public function perusahaanAsuransi()
    {
        return $this->belongsTo('\App\Models\PerusahaanAsuransi', 'perusahaan_asuransi_id', 'id');
    }

    public function jadwalPraktek()
    {
        return $this->belongsTo('\App\Models\JadwalPraktek', 'jadwal_praktek_id', 'id');
    }
}
