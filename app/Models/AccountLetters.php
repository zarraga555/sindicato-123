<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountLetters extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_name',
        'bank_name',
        'account_number',
        'account_type',
        'currency_type',
        'initial_account_amount',
        'created_by'
    ];

    public function users (): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
