<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Poliklinik extends Model
{
    use SoftDeletes;
    use Uuids;

    protected $table = "poliklinik";

    protected $fillable = ['kode_jaksehat','tampilkan_pemeriksan_penunjang','label_catatan_bebas','tampilkan_pada_display_antrian','ttv_dilakukan_oleh','tampilkan_upload_gambar','jenis_media_upload','tampil_antrian_ftkp','pembayaran_dilakukan_diawal','nomor_poli','nama','keterangan','aktif','jenis_unit','lokasi_id','unit_stock_id', 'setting_id','id_satu_sehat','biaya_pendaftaran', 'deleted_by'];

    public function deletedBy()
    {
        return $this->belongsTo(\App\User::class, 'deleted_by', 'id')->withTrashed()->withDefault(['name' => '-']);
    }

    public function unitStock()
    {
        return $this->belongsTo('App\Models\UnitStock')->withDefault(['nama_unit' => 'Belum Terhubung']);
    }

    public function lokasi()
    {
        return $this->belongsTo('App\Models\Lokasi')->withDefault(['nama_lokasi' => 'Belum Terhubung']);
    }

    public function mappingBpjs()
    {
        return $this->hasMany('App\Models\MappingPoliMobileJkn', 'poliklinik_id', 'id');
    }

    public function setting()
    {
        return $this->belongsTo('App\Models\Setting', 'setting_id', 'id');
    }



    public function scopeKunjunganPasienPerPoli($query, $tanggal_awal, $tanggal_akhir, $perusahaan_asuransi)
    {
        return $query->leftJoin('nomor_antrian', function ($join) use ($tanggal_awal, $tanggal_akhir, $perusahaan_asuransi) {
            $join->on('nomor_antrian.poliklinik_id', '=', 'poliklinik.id');
            $join->whereBetween(DB::raw('DATE(nomor_antrian.created_at)'), [$tanggal_awal,$tanggal_akhir]);
            if ($perusahaan_asuransi != 0) {
                $join->on('pendaftaran.id', '', 'nomor_antrian.pendaftaran_id');
                $join->where('pendaftaran.layanan', $perusahaan_asuransi);
            }
        })
        ->selectRaw('poliklinik.nama,poliklinik.nomor_poli, count(nomor_antrian.id) as jumlah_kunjungan')
        ->groupBy('poliklinik.id');
    }
}
