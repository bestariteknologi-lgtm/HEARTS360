<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class PendaftaranObatRacik extends Model
{
    use Uuids;

    protected $table = 'pendaftaran_obat_racik';

    protected $fillable = ['catatan','status','pendaftaran_id','aturan_pakai','kemasan','jumlah_kemasan','poliklinik_id','nomor_antrian_id', 'assesment_id', 'obat_pulang', 'signa', 'aturan_pakai_dua', 'is_diambil'];



    public function detail()
    {
        return $this->hasMany(\App\Models\PendaftaranObatRacikDetail::class);
    }

    public function satuan()
    {
        return $this->belongsTo(\App\Models\Satuan::class, 'kemasan', 'id');
    }

    public function nomorAntrian()
    {
        return $this->belongsTo(\App\Models\NomorAntrian::class, 'nomor_antrian_id');
    }

    public function poliklinik()
    {
        return $this->belongsTo(\App\Models\Poliklinik::class, 'poliklinik_id', 'id');
    }
}
