<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionApplication extends Model
{
    protected $fillable = [
        'user_id',
        'reference_no',
        'jenis_kenaikan',
        'jawatan_dipohon',
        'gred_dipohon',
        'ptj',
        'status',
        'submitted_at',
    ];

    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class, 'promotion_application_id');
    }
}
