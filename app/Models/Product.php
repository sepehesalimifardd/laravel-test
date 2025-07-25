<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'price',
        'stock',
        'category_id'
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(ProductVersion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class,"product_attribute")
            ->withPivot('value')
            ->withTimestamps();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
