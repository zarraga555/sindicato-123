<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function totalRegistration()
    {
        $this->sum = 0;
        $this->hasMany(CashFlow::class, 'user_id', 'id')->where('transaction_status', 'open')->where('cash_drawer_id', '!=', null)->each(function ($totalRegistration) {
            if ($totalRegistration->transaction_type_income_expense == 'income') {
                $this->sum += $totalRegistration->amount;
            }else{
                $this->sum -= $totalRegistration->amount;
            }
        });
        return $this->sum;
    }

    public function cashOnHand()
    {
        $this->sum = 0;
        $this->hasMany(CashFlow::class, 'user_id', 'id')->where('transaction_status', 'open')->where('cash_drawer_id', '!=', null)->where('payment_type', 'cash')->where('payment_status', 'paid')->each(function ($cashOnHand) {
            if ($cashOnHand->transaction_type_income_expense == 'income') {
                $this->sum += $cashOnHand->amount;
            }else{
                $this->sum -= $cashOnHand->amount;
            }
        });
        return $this->sum;
    }
}
