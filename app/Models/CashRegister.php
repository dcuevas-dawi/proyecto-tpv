<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opening_employee_id',
        'closing_employee_id',
        'opened_at',
        'closed_at',
        'opening_amount',
        'real_closing_amount',
        'theoretical_closing_amount',
        'difference',
        'comments',
        'status'
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Employee model
    public function openingEmployee()
    {
        return $this->belongsTo(Employee::class, 'opening_employee_id');
    }

    // Define the relationship with the Employee model
    public function closingEmployee()
    {
        return $this->belongsTo(Employee::class, 'closing_employee_id');
    }

    // Define the relationship with the Order model
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
