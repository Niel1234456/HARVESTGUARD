<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageAnalysis extends Model
{
    use HasFactory;

    protected $table = 'image_analysis';

    protected $fillable = [
        'disease_name',
        'detection_count',
        'average_confidence',
        'date_analyzed',
        'total_analyses',
      
    ];
}
