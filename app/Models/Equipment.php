<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit', 'quantity', 'image'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($equipment) {
            // Code to run before creating equipment
        });

        static::updating(function ($equipment) {
            // Code to run before updating equipment
        });

        static::deleting(function ($equipment) {
            // Adjust related quantities before deleting
            BorrowRequest::where('equipment_id', $equipment->id)->delete();
        });
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }
}
