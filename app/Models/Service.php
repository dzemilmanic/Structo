<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'professional_id',
        'title',
        'description',
        'category',
        'price_from',
        'price_to',
        'service_area',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
    ];

    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function requests()
    {
        return $this->hasMany(ServiceRequest::class);
    }
}