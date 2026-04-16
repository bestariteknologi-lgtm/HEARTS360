<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SegmentasiPasien extends Model
{
    use HasFactory;

    protected $table = 'segmentasi_pasien';
    protected $fillable = ['nama_segmentasi', 'setting_id'];
}
