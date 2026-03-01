<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionApplication extends Model
{
    protected $fillable = [
        'staff_id',
        'reference_no',
        'jenis_kenaikan',
        'jawatan_dipohon',
        'gred_dipohon',
        'ptj',
        'status',
        'submitted_at',
        'is_active',
        'reviewed_by_staff_id',
        'reviewed_at',
        'returned_at',
        'status_description',
        'metadata',
    ];

    public function pemohon()
    {
        return $this->belongsTo(Pemohon::class, 'staff_id', 'staff_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class, 'promotion_application_id');
    }
}
