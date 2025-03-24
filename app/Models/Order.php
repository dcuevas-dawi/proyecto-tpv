<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'user_id',
        'status',
        'total_price',
        'closed_at',
    ];

    /**
     * Relación con la mesa
     */
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Relación con los productos
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price_at_time');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'abierto');
    }
}
