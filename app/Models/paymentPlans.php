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
        'attachment',
        'user_id',
        'description',
        'cash_flow_id',
        'amount_paid',
        'collateral_id'
    ];

    public function loans (): BelongsTo
    {
        return $this->belongsTo(Loans::class, 'loan_id');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cashFlows(): BelongsTo
    {
        return $this->belongsTo(CashFlow::class, 'cash_flows_id');
    }
    
    public function collaterals(): BelongsTo
    {
        return $this->belongsTo(Collateral::class, 'collateral_id');
    }
}
