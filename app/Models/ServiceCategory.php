<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'category', 'slug');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'category', 'slug');
    }

    public function getRouteKeyName()
    {
        return 'id'; // Changed from 'slug' to 'id' for admin operations
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            if (empty($category->sort_order)) {
                $category->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}