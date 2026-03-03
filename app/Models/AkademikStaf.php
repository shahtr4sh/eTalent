<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkademikStaf extends Model
{
    protected $table = 'akademik_staf';

    protected $fillable = [
        'no_staf',
        'kod_tahap',
        'tahap_akademik',
        'kod_bidang',
        'tahun_tamat',
    ];
}
