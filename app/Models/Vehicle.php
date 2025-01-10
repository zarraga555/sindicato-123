<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $fillable = ['vehicle_license_plate', 'front_photo_vehicle', 'photo_behind_vehicle', 'phto_vehicle_ruat', 'user_id'];

    public function users (): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
