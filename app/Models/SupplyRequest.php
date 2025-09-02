<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SupplyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_id',
        'quantity',
        'farmer_id',
        'description',
        'requesting_number','status','is_released',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplyRequest) {
            $supplyRequest->requesting_number = 'REQ-' . strtoupper(Str::random(10)); // Example: REQ-ABC123XYZ
        });
    }

public function supply()
{
    return $this->belongsTo(Supply::class);
}

public function farmer()
{
    return $this->belongsTo(Farmer::class);
}
    
    
}
