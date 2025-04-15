<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashFlow extends Model
{
    use SoftDeletes;
    protected $table = 'cash_flows'; //
    protected $fillable = [
        'user_id',
        'amount',
        'detail',
        'transaction_type_income_expense',
        'account_bank_id',
        'items_id',
        'vehicle_id',
        'roadmap_series',
        'type_transaction',
        'description',
        'registration_date',
        'payment_type',
        'payment_status',
        'transaction_status',
        'cash_drawer_id'
        //        'driver_id',
        //        'partner_id'
    ];

    public function users(): BelongsTo
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

    public static function totalRecordedByTheSystem($cash_drawer_id)
    {
        $final_money = 0;
        // Procesar transacciones abiertas
        $cash_flows = CashFlow::where('transaction_status', 'open')
            ->where('cash_drawer_id', $cash_drawer_id)
            ->get();

        if ($cash_flows->isEmpty()) {
            throw new \Exception('No hay transacciones abiertas para cerrar.');
        }

        foreach ($cash_flows as $account) {
            $amount = $account->amount;

            if ($account->transaction_type_income_expense === 'income') {
                $final_money += $amount;
            } else {
                $final_money -= $amount;
            }
        }
        return $final_money;
    }
}
