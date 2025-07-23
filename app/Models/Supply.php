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
            // Code to run before creating a supply
        });

        static::updating(function ($supply) {
            // Code to run before updating a supply
        });

        static::deleting(function ($supply) {
            // Adjust related quantities before deleting
            SupplyRequest::where('supply_id', $supply->id)->delete();
        });
    }

    public function supplyRequests()
    {
        return $this->hasMany(SupplyRequest::class);
    }
}