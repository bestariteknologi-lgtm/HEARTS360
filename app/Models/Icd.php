<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icd extends Model
{
    protected $table = "icds";
    public $timestamps = false;
    protected $fillable = ['code','name_en'];
}
