<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'farmer_id',    
        'message',      
        'is_read',
        'link', 
        'type',
        'action_type'   
    ];

    public function farmer()
{
    return $this->belongsTo(Farmer::class);
}
}
