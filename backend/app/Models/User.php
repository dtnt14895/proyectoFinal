<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       
        'person_id',
        'email',
        'password', 
        'remember_token' ,
        'rol_id',      
        'create_by',      
        'update_by' 
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
        'email_verified_by' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function person(): BelongsTo
    {
        return $this->BelongsTo(Person::class,'person_id');
    }

    public function rol(): BelongsTo
    {
        return $this->BelongsTo(Rol::class,'rol_id');
    }

    public function create_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }

    public function update_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
