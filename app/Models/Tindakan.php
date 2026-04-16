<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Tindakan extends Model
{
    use SoftDeletes;
    use Uuids;

    protected $table = "tindakan";

    protected $fillable = ['kelompok_tindakan','mcu_bpjs','perusahaan_asuransi_id','t_klinik_persentase','t_asisten_persentase','t_dokter_persentase','setting_id','kategori','administratif','kode','kode_sts','ditanggung_bpjs','input_otomatis','tindakan','tarif_umum','tarif_bpjs','tarif_perusahaan','pembagian_tarif', 'jenis','iterasi','quota','pelayanan','poliklinik_id','kode_referensi_tindakan_bpjs','deleted_by','aktif','t_dokter','t_asisten','t_klinik','flag_grouping'];

    protected $casts = [
        'input_otomatis' => 'boolean',
        'ditanggung_bpjs' => 'boolean',
        'administratif' => 'boolean',
        'aktif' => 'boolean',
    ];

    public function perusahaanAsuransi()
    {
        return $this->belongsTo('App\Models\PerusahaanAsuransi', 'perusahaan_asuransi_id', 'id');
    }

    public function getPembagianTarifAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        // Try to unserialize first (for existing serialized data)
        $unserialized = @unserialize($value);
        if ($unserialized !== false) {
            return $unserialized;
        }

        // If unserialize fails, try to decode as JSON
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // If both fail, return null or empty array
        return null;
    }

    public function setPembagianTarifAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['pembagian_tarif'] = serialize($value);
        } else {
            $this->attributes['pembagian_tarif'] = $value;
        }
    }

    public function getTDokterAttribute($value)
    {
        if ($this->pembagian_tarif && isset($this->pembagian_tarif['dokter-umum'])) {
            return $this->pembagian_tarif['dokter-umum'];
        }
        return $value;
    }

    public function getTAsistenAttribute($value)
    {
        if ($this->pembagian_tarif && isset($this->pembagian_tarif['asisten-umum'])) {
            return $this->pembagian_tarif['asisten-umum'];
        }
        return $value;
    }

    public function getTKlinikAttribute($value)
    {
        if ($this->pembagian_tarif && isset($this->pembagian_tarif['klinik-umum'])) {
            return $this->pembagian_tarif['klinik-umum'];
        }
        return $value;
    }

    public function setTDokterAttribute($value)
    {
        $this->attributes['t_dokter'] = $value;
        if ($this->pembagian_tarif) {
            $pembagian_tarif = $this->pembagian_tarif;
            $pembagian_tarif['dokter-umum'] = $value;
            $this->pembagian_tarif = $pembagian_tarif;
        }
    }

    public function setTAsistenAttribute($value)
    {
        $this->attributes['t_asisten'] = $value;
        if ($this->pembagian_tarif) {
            $pembagian_tarif = $this->pembagian_tarif;
            $pembagian_tarif['asisten-umum'] = $value;
            $this->pembagian_tarif = $pembagian_tarif;
        }
    }

    public function setTKlinikAttribute($value)
    {
        $this->attributes['t_klinik'] = $value;
        if ($this->pembagian_tarif) {
            $pembagian_tarif = $this->pembagian_tarif;
            $pembagian_tarif['klinik-umum'] = $value;
            $this->pembagian_tarif = $pembagian_tarif;
        }
    }

    public function icd()
    {
        return $this->belongsTo('App\Models\Icd', 'code');
    }

    public function indikator()
    {
        return $this->hasMany('App\Models\IndikatorPemeriksaanLab', 'tindakan_id');
    }

    public function bhp()
    {
        return $this->hasMany('App\Models\TindakanBHP');
    }
    public function poliklinik()
    {
        return $this->belongsTo('App\Models\Poliklinik');
    }

    public function deletedBy()
    {
        return $this->belongsTo(\App\User::class, 'deleted_by', 'id')->withDefault(['name' => '-']);
    }
}
