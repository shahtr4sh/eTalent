<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemohon extends Model
{
    protected $table = 'pemohon';

    protected $primaryKey = 'staff_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function jawatanStaf()
    {
        return $this->hasMany(\App\Models\JawatanStaf::class, 'no_staf', 'staff_id');
    }

    public function jawatanStafTerkini()
    {
        return $this->hasOne(\App\Models\JawatanStaf::class, 'no_staf', 'staff_id')
            ->where('terkini', '1')
            ->orderByDesc('aktif'); // optional
    }

    public function jabatanStaf()
    {
        return $this->hasOne(\App\Models\JabatanStaf::class, 'no_staf', 'staff_id');
    }

    public function akademikStaf()
    {
        return $this->hasMany(\App\Models\AkademikStaf::class, 'no_staf', 'staff_id');
    }

    protected $fillable = [
        'staff_id','nama','gred_semasa','jawatan_semasa',
        'ptj_fakulti','jabatan','emel_rasmi','no_telefon'
    ];
}
