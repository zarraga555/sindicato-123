<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemsCashFlow extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type_income_expense',
        'created_by',
    ];

    public function users (): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
