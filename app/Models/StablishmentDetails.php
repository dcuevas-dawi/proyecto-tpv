<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StablishmentDetails extends Model
{
    use HasFactory;

    protected $table = 'stablishment_details';

    protected $fillable = [
        'user_id',
        'commercial_name',
        'legal_name',
        'cif',
        'address',
        'postal_code',
        'city',
        'province',
        'country',
        'phone',
        'email',
        'logo_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
