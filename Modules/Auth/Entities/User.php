<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends  = ['user_token'];


    protected static function newFactory()
    {
        return \Modules\Auth\Database\Factories\UserFactory::new();
    }


    public function getUserTokenAttribute()
    {
        if(!$this->currentAccessToken()) return $this->createToken("bakery")->plainTextToken;

        return $this->currentAccessToken();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name','username','usergroup_id','username','email','phone','password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function usergroup()
    {
        return $this->belongsTo(Usergroup::class);
    }
}
