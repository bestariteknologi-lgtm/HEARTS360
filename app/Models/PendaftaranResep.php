<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendaftaranResep extends Model
{
    use Uuids;
    use SoftDeletes;

    protected $table = "pendaftaran_resep";

    protected $attributes = [
        'obat_pulang' => 0,
        'is_diambil' => 0,
    ];

    protected $fillable = [
        'pendaftaran_tindakan_id',
        'keterangan',
        'stock_sebelum',
        'stock_sesudah',
        'status',
        'fee_dokter',
        'fee_asisten',
        'satusehat_medication_id',
        'satusehat_medication_request_id',
        'tindakan_id',
        'discount',
        'diskon_persen',
        'is_bpjs',
        'nama_barang',
        'nama_dokter',
        'pendaftaran_id',
        'nomor_antrian_id',
        'barang_id',
        'jumlah',
        'created_by',
        'updated_by',
        'deleted_by',
        'satuan_terkecil_id',
        'aturan_pakai',
        'harga_modal',
        'kdObatSK',
        'medication_dispense_id',
        'jenis',
        'session_id',
        'harga',
        'asisten_id',
        'poliklinik_id',
        'dokter_id',
        'tanggal',
        'jam',
        'indikasi',
        'barang_batch_id',
        'obat_pulang',
        'assesment_id',
        'frekuensi',
        'cara_pemberian',
        'status_pemberian',
        'signa',
        'aturan_pakai_dua',
        'is_diambil'
    ];

    public function pendaftaran()
    {
        return $this->belongsTo('App\Models\Pendaftaran');
    }

    public function barangBatch()
    {
        return $this->belongsTo('App\Models\BarangBatch', 'barang_batch_id', 'id');
    }

    public function resepBatches()
    {
        return $this->hasMany('App\Models\PendaftaranResepBatch', 'pendaftaran_resep_id');
    }

    public function nomorAntrian()
    {
        return $this->belongsTo('App\Models\NomorAntrian');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'dokter_id', 'id');
    }
    public function poliklinik()
    {
        return $this->belongsTo('App\Models\Poliklinik');
    }

    public function barang()
    {
        return $this->belongsTo('App\Models\Barang')->withTrashed()->withDefault(['nama_barang' => '-']);
    }


    public function satuanObat()
    {
        return $this->belongsTo(\App\Models\Satuan::class, 'satuan', 'id');
    }

    public function tindakan()
    {
        return $this->belongsTo('App\Models\Tindakan')->withTrashed();
    }
}
