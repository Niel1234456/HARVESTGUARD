<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExistingFarmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname', 'lastname', 'middle_initial', 'age', 'birthday', 'email', 'phone_number', 'address_1', 'address_2'
    ];
}
