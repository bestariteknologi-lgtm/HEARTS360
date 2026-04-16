<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Pendaftaran extends Model
{
    use SoftDeletes;
    use Uuids;

    /**
     * Reserved key inside serialized tanda_tanda_vital for inline-edit audit trail (no extra DB column).
     */
    public const TTV_CHANGELOG_KEY = '__ttv_changelog';

    public const TTV_CHANGELOG_MAX_ENTRIES = 400;

    protected $table = "pendaftaran";

    protected $fillable = [
        'kode',
        'pasien_id',
        'petugas_resep_user_id',
        'setting_id',
        'pengkajian_awal_ralan',
        'perusahaan_asuransi_id',
        'perusahaan_asuransi',
        'status_pembayaran',
        'has_ttv',
        'created_at',
        'status_pelayanan',
        'status_alergi',
        'tanda_tanda_vital',
        'umur',
        'jenis_pendaftaran',
        'jenis_rujukan',
        'nama_pasien',
        'deleted_by',
        'nama_perujuk',
        'sistole',
        'diastole',
        'pendaftaran_jenis_identitas_default',
        'no_surat',
        'alasan_batal_kunjungan',
        'tanggal_berlaku',
        'penanggung_jawab',
        'hubungan_pasien',
        'alamat_penanggung_jawab',
        'no_hp_penanggung_jawab',
        'status_kehamilan',
        'pemeriksaan_klinis',
        'anamnesa',
        'nomor_asuransi',
        'skrining_visual_rajal',
        'telaah_resep',
        'deskripsi_telaah_resep',
        'pemeriksaan_gigi',
        'deskripsi_pemeriksaan_gigi',
        'telaah_obat',
        'deskripsi_telaah_obat',
        'signature',
        'general_consent_user_id',
        'general_consent_ttd',
        'general_consent_penanggung_jawab',
        'skrining_visual',
        'biaya_pendaftaran',
        'metode_pembayaran',
        'status_pembayaran',
        'waktu_pembayaran',
        'catatan_pembayaran',
        'total_bayar',
        'jumlah_bayar',
        'kembalian',
        'diskon',
        'ppn',
        'nomor_referensi_pembayaran',
        'data_anc',
        'data_inc',
        'data_pnc',
        'data_imunisasi',
        'user_id_kasir',
        'nomor_resep',
        'cek_resep',
        'gula_darah_sewaktu',
        'gula_darah_puasa',
        'gula_darah_hba1c',
    ];

    protected $casts = [
        'skrining_visual' => 'array',
        'data_anc' => 'array',
        'data_inc' => 'array',
        'data_pnc' => 'array',
        'data_imunisasi' => 'array',
    ];

    public function pasien()
    {
        return $this->belongsTo('App\Models\Pasien', 'pasien_id', 'id');
    }

    public function poliklinik()
    {
        return $this->belongsTo('App\Models\Poliklinik', 'poliklinik_id', 'id');
    }

    public function dokter()
    {
        return $this->belongsTo('App\User', 'dokter_id', 'id');
    }

    public function generalConsent()
    {
        return $this->belongsTo('App\User', 'general_consent_user_id', 'id');
    }

    public function perusahaanAsuransi()
    {
        return $this->belongsTo('App\Models\PerusahaanAsuransi', 'perusahaan_asuransi_id', 'id');
    }

    public function userKasir()
    {
        return $this->belongsTo('App\User', 'user_id_kasir', 'id');
    }

    public function petugasTelaahResep()
    {
        return $this->belongsTo('App\User', 'petugas_resep_user_id', 'id');
    }

    public function getTandaTandaVitalAttribute($value)
    {
        return unserialize($value);
    }

    public static function appendTtvChangelogEntry(array &$data, string $field, $oldValue, $newValue, ?int $userId, string $userName = ''): void
    {
        if ($field === self::TTV_CHANGELOG_KEY || strpos($field, '__') === 0) {
            return;
        }
        $key = self::TTV_CHANGELOG_KEY;
        if (!isset($data[$key]) || !is_array($data[$key])) {
            $data[$key] = [];
        }
        $oldStr = self::ttvChangelogScalar($oldValue);
        $newStr = self::ttvChangelogScalar($newValue);
        if ($oldStr === $newStr) {
            return;
        }
        $data[$key][] = [
            'at' => now()->format('Y-m-d H:i:s'),
            'uid' => $userId,
            'user' => $userName,
            'field' => $field,
            'from' => $oldStr,
            'to' => $newStr,
        ];
        if (count($data[$key]) > self::TTV_CHANGELOG_MAX_ENTRIES) {
            $data[$key] = array_slice($data[$key], -self::TTV_CHANGELOG_MAX_ENTRIES);
        }
    }

    /**
     * When replacing clinical TTV fields in bulk, keep existing changelog entries.
     *
     * @param  array|null  $existingFullTtv  Unserialized tanda_tanda_vital before overwrite
     */
    public static function mergeTtvChangelogPreserve($existingFullTtv, array $newClinicalTtv): array
    {
        $key = self::TTV_CHANGELOG_KEY;
        $changelog = [];
        if (is_array($existingFullTtv) && isset($existingFullTtv[$key]) && is_array($existingFullTtv[$key])) {
            $changelog = $existingFullTtv[$key];
        }
        $newClinicalTtv[$key] = $changelog;

        return $newClinicalTtv;
    }

    private static function ttvChangelogScalar($v): string
    {
        if ($v === null) {
            return '';
        }
        if (is_bool($v)) {
            return $v ? '1' : '0';
        }

        return trim((string) $v);
    }

    public function getSkriningVisualRajalAttribute($value)
    {
        return unserialize($value);
    }

    public function getPemeriksaanKlinisAttribute($value)
    {
        return unserialize($value);
    }

    public function getTelaahResepAttribute($value)
    {
        return unserialize($value);
    }

    public function getDeskripsiTelaahResepAttribute($value)
    {
        return unserialize($value);
    }

    public function getTelaahObatAttribute($value)
    {
        return unserialize($value);
    }

    public function getDeskripsiTelaahObatAttribute($value)
    {
        return unserialize($value);
    }

    public function getPemeriksaanGigiAttribute($value)
    {
        return unserialize($value);
    }

    public function getDeskripsiPemeriksaanGigiAttribute($value)
    {
        return unserialize($value);
    }

    public function feeTindakan()
    {
        return $this->hasMany('App\Models\PendaftaranTindakan');
    }

    public function obatRacik()
    {
        return $this->hasMany('App\Models\PendaftaranObatRacik');
    }

    public function resepNonRacik()
    {
        return $this->hasMany('App\Models\PendaftaranResep')->where('jenis', 'non racik');
    }

    public function resepBhp()
    {
        return $this->hasMany('App\Models\PendaftaranResep')->where('jenis', 'bhp');
    }

    public function tindakan()
    {
        return $this->belongsTo('App\Models\Tindakan', 'tindakan_id', 'id');
    }

    public function jenisLayanan()
    {
        return $this->belongsTo('App\Models\PerusahaanAsuransi', 'jenis_layanan', 'id');
    }


    public function nomorAntrian()
    {
        return $this->hasMany('App\Models\NomorAntrian', 'pendaftaran_id', 'id');
    }

    public function keluarga()
    {
        return $this->belongsTo('App\Models\Keluarga', 'pasien_id', 'pasien_id')->where('penanggung_jawab', 1);
    }

    public function setting()
    {
        return $this->belongsTo('App\Models\Setting', 'setting_id', 'id');
    }
}
