<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class PendaftaranDiagnosa extends Model
{
    use Uuids;
    use SoftDeletes;

    protected $table = "pendaftaran_diagnosa";

    protected $fillable = ['tbm_icd_id','nomor_resep','nomor_antrian_farmasi','nama_diagnosa','catatan','session_id','pendaftaran_id','poliklinik_id','nomor_antrian_id','kode_icd','setting_id','satusehat_condition_id','status_diagnosa', 'assesment_id', 'jenis_diagnosa', 'insert_by'];

    public function pendaftaran()
    {
        return $this->belongsTo('App\Models\Pendaftaran');
    }

    public function icd()
    {
        return $this->belongsTo('App\Models\Icd', 'tbm_icd_id')->withDefault(['kode' => '','indonesia' => '']);
    }
}
