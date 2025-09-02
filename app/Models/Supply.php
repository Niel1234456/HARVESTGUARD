<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit', 'quantity', 'image'];
        protected static function boot()
    {
        parent::boot();

        static::creating(function ($supply) {
        });

        static::updating(function ($supply) {
        });

        static::deleting(function ($supply) {
            SupplyRequest::where('supply_id', $supply->id)->delete();
        });
    }

    public function supplyRequests()
    {
        return $this->hasMany(SupplyRequest::class);
    }
}