<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class Pasien extends Model
{
    use SoftDeletes;
    use Uuids;

    protected $table = "pasien";

    protected $fillable = [
        'nomor_ktp',
        'jenis_identitas',
        'nomor_kk',
        'nik_ibu',
        'general_consent_user_id',
        'general_consent_ttd',
        'general_consent_penanggung_jawab',
        'general_consent_status_penanggung_jawab',
        'nama',
        'sumber_pencarian',
        'nama_capital',
        'setting_id',
        'tempat_lahir',
        'deleted_by',
        'tanggal_lahir',
        'nomor_hp',
        'email',
        'pekerjaan',
        'alamat',
        'alamat_berbeda_dari_ktp',
        'aktif',
        'rt_rw',
        'rt',
        'rw',
        'jenis_kelamin',
        'pendidikan',
        'agama',
        'status_pernikahan',
        'nama_suami_istri',
        'nama_ibu',
        'nama_ayah',
        'nomor_asuransi',
        'kelas_bpjs',
        'jenis_peserta_bpjs',
        'kewarganegaraan',
        'golongan_darah',
        'privilage_khusus',
        'penanggung_jawab',
        'suku_bangsa',
        'hubungan_pasien',
        'alamat_penanggung_jawab',
        'nomor_hp_penanggung_jawab',
        'nomor_rekam_medis',
        'kode_rfid',
        'penjamin',
        'no_rekam_medis_lama',
        'village_id',
        'province_id',
        'district_id',
        'regency_id',
        'inisial',
        'satusehat_id',
        'foto',
        'status_keluarga',
        'warga_negara',
        'no_pasport',
        'asal_negara',
        'kelainan_pasien',
        'rt_berbeda',
        'rw_berbeda',
        'village_id_berbeda',
        'district_id_berbeda',
        'regency_id_berbeda',
        'province_id_berbeda',
        'anak_baru_lahir',
        'nama_kepala_keluarga',
        'suku',
        'rpd',
        'rpk',
        'icd_berbahaya',
        'referensi',
        'nama_instansi',
        'informasi_lainnya',
        'sudah_skrining_ckg',
        'membership_id',
        'id_segmentasi',
        'nama_panggilan',
        'catatan',
        'data_imunisasi_dewasa',
        'data_imunisasi_anak',
    ];

    protected $casts = [
        'kelainan_pasien' => 'array',
        'anak_baru_lahir' => 'array',
        'sudah_skrining_ckg' => 'boolean',
        'data_imunisasi_dewasa' => 'array',
        'data_imunisasi_anak' => 'array',
    ];

    public function wilayahAdministratifIndonesia()
    {
        return $this->belongsTo('App\Models\WilayahAdministratifIndonesia', 'village_id', 'village_id');
    }

    public function rujukan(Type $var = null)
    {
        return $this->hasMany('App\Models\RujukanInternal');
    }

    public function riwayatPenyakit()
    {
        return $this->hasMany('App\Models\RiwayatPenyakit', 'pasien_id', 'id')->where('tbm_icd', '!=', '');
    }

    public function pasienRiwayatAlergi()
    {
        return $this->belongsTo('App\Models\PasienRiwayatAlergi');
    }

    public function pasienRiwayatPenyakit()
    {
        return $this->belongsTo('App\Models\PasienRiwayatPenyakit');
    }

    public function paketIterasi()
    {
        return $this->hasMany('App\Models\PaketIterasi');
    }


    public function alergi()
    {
        return $this->hasMany('App\Models\Alergi', 'id', 'alergi_id');
    }

    public function penyakitKronisPasien()
    {
        return $this->hasMany('App\Models\PenyakitKronisPasien');
    }

    public function generalConsentUser()
    {
        return $this->belongsTo('App\User', 'general_consent_user_id', 'id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(\App\User::class, 'deleted_by', 'id')->withDefault(['name' => '-']);
    }

    // public function getJenisKelaminAttribute($value)
    // {
    //     return $value=='L'?'LAKI LAKI':'PEREMPUAN';
    // }

    // public function getNomorKTPAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }


    protected $appends = ['umur'];

    public function getUmurAttribute()
    {
        $tanggal_lahir  = new \DateTime($this->tanggal_lahir);
        $now            = new \DateTime();
        $umur           = $now->diff($tanggal_lahir);
        return $this->tanggal_lahir == '0000-00-00' ? '0' : $umur->y;
    }

    public function membership()
    {
        return $this->belongsTo('App\Models\Membership', 'membership_id', 'id');
    }

    public function segmentasi()
    {
        return $this->belongsTo('App\Models\SegmentasiPasien', 'id_segmentasi', 'id');
    }
}
