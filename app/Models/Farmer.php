<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Farmer extends Authenticatable

{
    use HasFactory, Notifiable;
    protected $guard = 'farmer';    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'phone',
        'birth_date',
        'gender',
        'street_address',
        'street_address2',
        'country',
        'city',
        'region',
        'postal_code',
        'profile_picture',  'farmers_activity', 'id_type', 'id_number',  'crop_picture','province','first_name', 'last_name', 'middle_initial',  // <-- Add this line

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

        // Define the relationship with SupplyRequest
        public function supplyRequests()
        {
            return $this->hasMany(SupplyRequest::class);
        }
    
        public function borrowRequests()
        {
            return $this->hasMany(BorrowRequest::class);
        }

        public function notifications()
        {
            return $this->hasMany(Notification::class);
        }
}
