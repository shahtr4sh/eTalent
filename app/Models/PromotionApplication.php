<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionApplication extends Model
{
    use SoftDeletes;

    protected $table = 'promotion_applications';

    protected $fillable = [
        'staff_id',
        'reference_no',
        'status',
        'is_active',
        'metadata',
        'content',
        'reviewed_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'content'  => 'array',
        'is_active' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class, 'promotion_application_id');
    }
}
