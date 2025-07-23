<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Specify the table name (optional if using default naming conventions)
    protected $table = 'notifications';

    // Specify which attributes are mass assignable
    protected $fillable = [
        'farmer_id',    // The ID of the associated farmer
        'message',      // The success or notification message
        'is_read',
        'link', 
        'type',
        'action_type'   // Indicates whether the notification has been read
    ];

    public function farmer()
{
    return $this->belongsTo(Farmer::class);
}
}
