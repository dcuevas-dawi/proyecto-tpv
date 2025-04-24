<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'order_id',
        'user_id',
        'status',
        'total_price',
        'closed_at',
        'employee_id',
        'cash_register_id',
    ];

    // Define the relationship with the Table model
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Define the relationship with the Product model
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price_at_time');
    }

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Return open orders
    public function scopeOpen($query)
    {
        return $query->where('status', 'abierto');
    }

    // Define the relationship with the Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // Define the relationship with the CashRegister model
    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
}
