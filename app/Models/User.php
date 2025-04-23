<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable  // User representa cada establecimiento
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    // Define the relationship with the Employee model
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // Define the relationship with the Table model
    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    // Define the relationship with the Product model
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Define the relationship with the StablishmentDetails model
    public function stablishmentDetails()
    {
        return $this->hasOne(StablishmentDetails::class);
    }
}
