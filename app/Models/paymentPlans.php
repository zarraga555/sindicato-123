<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class paymentPlans extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'instalmentNumber',
        'datePayment',
        'paymentStatus',
        'amount',
        'loan_id',
        'attachment'
    ];

    public function loans (): BelongsTo
    {
        return $this->belongsTo(Loans::class, 'loan_id');
    }
}
