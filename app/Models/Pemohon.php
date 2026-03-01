<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemohon extends Model
{
    protected $table = 'pemohon';

    protected $primaryKey = 'staff_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'staff_id','nama','gred_semasa','jawatan_semasa',
        'ptj_fakulti','jabatan','emel_rasmi','no_telefon'
    ];
}
