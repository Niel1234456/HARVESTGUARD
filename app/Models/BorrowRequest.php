<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = ['farmer_id', 'equipment_id', 'quantity', 'borrow_number', 'description', 'return_date','status','is_released', ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->borrow_number = 'BR-' . strtoupper(Str::random(10));
        });
    }
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }
    
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
 