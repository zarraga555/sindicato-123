<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashDrawer extends Model
{
    use SoftDeletes;

    protected $casts = [
        'denominaciones' => 'array', // Laravel convierte JSON en array automáticamente
    ];

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'initial_cash',
        'final_money',
        'digital_payments',
        'total_calculated',
        'total_declared',
        'difference',
        'status',
        'denominations'
    ];

    /**
     * Relación con el usuario que realiza el arqueo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para obtener solo los arqueos abiertos.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope para obtener solo los arqueos cerrados.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope para obtener solo los arqueos parciales.
     */
    public function scopeParcial($query)
    {
        return $query->where('status', 'parcial');
    }
}
