<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class PerusahaanAsuransi extends Model
{
    use SoftDeletes;
    use Uuids;

    protected $table = "perusahaan_asuransi";

    protected $fillable = ['nama_perusahaan','harga_tindakan_khusus','berikan_harga_khusus','aktif','setting_id','urutan'];

    protected $casts = [
        'aktif' => 'boolean',
        'berikan_harga_khusus' => 'boolean',
    ];

    /**
     * Scope: urutan tampilan (custom per faskes). Jika urutan tidak diatur (null/0) = tampil sesuai default (nama).
     */
    public function scopeOrderByUrutan($query)
    {
        return $query->orderByRaw('COALESCE(NULLIF(urutan, 0), 999999) ASC')->orderBy('nama_perusahaan', 'ASC');
    }
}
