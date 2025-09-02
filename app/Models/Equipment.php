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
        });

        static::updating(function ($equipment) {
        });

        static::deleting(function ($equipment) {
            BorrowRequest::where('equipment_id', $equipment->id)->delete();
        });
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }
}
