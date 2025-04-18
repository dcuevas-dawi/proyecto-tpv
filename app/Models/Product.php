<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'category',
        'active'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity', 'price_at_time');
    }

}
