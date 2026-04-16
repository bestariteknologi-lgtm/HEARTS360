<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class PendaftaranTindakan extends Model
{
    use Uuids;

    protected $table = "pendaftaran_tindakan";

    protected $fillable = [
        'tindakan_id',
        'catatan',
        'prosedur_tindakan',
        'urutan_pemeriksaan',
        'nomor_antrian_id',
        'pendaftaran_id',
        'poliklinik_id',
        'nama_tindakan',
        'nama_dokter',
        'fee',
        'ditanggung_bpjs',
        'tindakan_paket',
        'anamnesa',
        'kode_gigi',
        'session_id',
        'tbm_icd_id',
        'qty',
        'discount',
        'diskon_persen',
        'poliklinik_id',
        'pemeriksaan_klinis',
        'dokter_id',
        'tanggal',
        'jam',
        'asisten_id',
        'assesment_id'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo('App\Models\Pendaftaran');
    }

    public function tindakan()
    {
        return $this->belongsTo('App\Models\Tindakan')->withTrashed()->withDefault(['tindakan' => '-']);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'dokter_id', 'id');
    }


    public function tbm()
    {
        return $this->belongsTo(TbmIcd::class, 'tbm_icd_id', 'id');
    }

    public function hasilPemeriksaan()
    {
        return $this->hasMany('App\Models\HasilPemeriksaanLab');
    }

    public function poliklinik()
    {
        return $this->belongsTo('App\Models\Poliklinik', 'poliklinik_id', 'id');
    }

    public function nomorAntrian()
    {
        return $this->belongsTo('App\Models\NomorAntrian', 'nomor_antrian_id', 'id');
    }
}
