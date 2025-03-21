<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loans extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vehicle_id',
//        'driver_id',
//        'partner_id',
        'driver_partner_name',
        'loan_start_date',
        'numberInstalments',
        'debtStatus',
        'amountLoan',
        //News columns
        'interest_rate',
        'total_debt',
        'user_type', //TEMPORALMENTE
        'payment_frequency',
        'cash_flows_id',
        'interest_payment_method',
        'user_id',
        'description'
    ];

    public function cashFlows(): BelongsTo
    {
        return $this->belongsTo(CashFlow::class, 'cash_flows_id');
    }

    public function vehicles(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function installments()
    {
        return $this->hasMany(paymentPlans::class, 'loan_id');
    }

//    public function drivers(): BelongsTo
//    {
//        return $this->belongsTo(Driver::class, 'driver_id');
//    }

//    public function partners(): BelongsTo
//    {
//        return $this->belongsTo(Partner::class, 'partner_id');
//    }
}
