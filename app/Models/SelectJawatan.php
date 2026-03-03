<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectJawatan extends Model
{
    protected $table = 'select_jawatan';
    protected $primaryKey = 'id_daftar';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_daftar',
        'kodJawatan',
        'nama_jawatan',
        'nama_jawatan_bi',
        'kod_kump',
        'gredJawatan',
        'kategori',
    ];

    protected $casts = [
        //
    ];
}
