<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Membership extends Model
{
    use SoftDeletes;
    use Uuids;

    protected $table = 'memberships';

    protected $fillable = [
        'nama',
        'setting_id',
    ];
}
