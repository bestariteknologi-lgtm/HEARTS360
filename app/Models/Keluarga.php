<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    use Uuids;

    protected $table = 'keluarga';

    protected $fillable = ['nama', 'pasien_id', 'nomor_telpon', 'penanggung_jawab', 'alamat', 'jenis_kelamin', 'tanggal_lahir', 'pendidikan', 'pekerjaan', 'pekerjaan_lainnya', 'ruang_lingkup_pekerjaan'];

    public function pasien()
    {
        return $this->belongsTo('App\Models\Pasien');
    }
}
