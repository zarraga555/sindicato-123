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
        'driver_partner',
        'dateLoan',
        'numberInstalments',
        'debtStatus',
        'amountLoan'
    ];

    public function vehicles(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
