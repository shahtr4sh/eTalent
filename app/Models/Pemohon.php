<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemohon extends Model
{
    protected $table = 'pemohon';

    protected $primaryKey = 'staff_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function jawatanSemasa()
    {
        return $this->hasOne(JawatanStaf::class, 'no_staf', 'staff_id')
            ->where('terkini', '1')
            ->where('aktif', '1');
    }

    public function jabatanSemasa()
    {
        return $this->hasOne(JabatanStaf::class, 'no_staf', 'staff_id')
            ->where('terkini', '1')
            ->where('aktif', '1');
    }

    protected $fillable = [
        'staff_id','nama','gred_semasa','jawatan_semasa',
        'ptj_fakulti','jabatan','emel_rasmi','no_telefon'
    ];
}
