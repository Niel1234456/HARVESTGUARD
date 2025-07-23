<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // For generating unique numbers

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = ['farmer_id', 'equipment_id', 'quantity', 'borrow_number', 'description', 'return_date','status','is_released', ];

    // Generate a unique borrow number when creating a new BorrowRequest
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a unique borrow number with random string
            $model->borrow_number = 'BR-' . strtoupper(Str::random(10));
        });
    }
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id'); // Specify the foreign key explicitly
    }
    
    public function equipment()
    {
        return $this->belongsTo(Equipment::class); // Ensure this points to the correct Equipment model
    }
}
 