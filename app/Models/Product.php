<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'image',
        'address',
        'whatsapp_number',
        'category',
    ];

    protected $casts = [
        'price' => 'decimal:0',
    ];

    /**
     * Scope untuk search products berdasarkan name atau description.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Accessor untuk formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function views()
    {
        return $this->hasMany(ProductView::class);
    }
}
