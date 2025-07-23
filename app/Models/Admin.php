<?php

namespace App\Models;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';
    public function getEmailVerificationUrl()
    {
        $url = URL::temporarySignedRoute(
            'admin.verification.verify',
            now()->addMinutes(60),
            ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())]
        );
    
        \Log::info('Generated Verification URL: ' . $url); // Log the URL
    
        return $url;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'contact_number', 
        'address',        
        'age',           
        'birthday',       
        'office_picture', 
        'profile_picture',  
        'first_name',       
        'last_name',        
        'middle_initial',   
        'position',         
        'id_type',          
        'gender',           
        'postal_code',      
        'city',             
        'province',         
        'country', 
        'region',         

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

  
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function farmers()
    {
        return $this->hasMany(Farmer::class);
    }

    public function sendEmailVerificationNotification()
{
    $this->notify(new CustomVerifyEmail);
}


}
