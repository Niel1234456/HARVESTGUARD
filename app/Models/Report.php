<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Specify the table name (if it's different from the default 'reports')
    protected $table = 'reports';

    // Specify the columns that can be mass-assigned
    protected $fillable = [
        'farmer_name',
        'file_path',
    ];
}
