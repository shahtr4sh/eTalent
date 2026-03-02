<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawatanStaf extends Model
{
    protected $table = 'jawatan_staf';
    protected $primaryKey = 'id_rec_jwt';
    public $incrementing = false;
    protected $keyType = 'string';
}
