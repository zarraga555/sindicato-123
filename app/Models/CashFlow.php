<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashFlow extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'amount',
        'detail',
        'transaction_type_income_expense',
        'account_bank_id',
        'items_id',
        'vehicle_id',
//        'driver_id',
//        'partner_id'
    ];

    public function users (): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function banks(): BelongsTo
    {
        return $this->belongsTo(AccountLetters::class, 'account_bank_id');
    }

    public function itemsCashFlow(): BelongsTo
    {
        return $this->belongsTo(ItemsCashFlow::class, 'items_id');
    }

    public function vehicles(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
