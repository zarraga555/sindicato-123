<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collateral extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'driver_partner_name',
        'start_date',
        'instalments',
        'status',
        'amount',
        'registration_date',
        'user_type',
        'payment_frequency',
        'cash_flows_id',
        'description',
        'user_id'
    ];

    /**
     * Relación con el vehículo.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Relación con el flujo de caja.
     */
    public function cashFlow()
    {
        return $this->belongsTo(CashFlow::class, 'cash_flows_id');
    }

    /**
     * Relación con el usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
