<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'condition',
        'stock',
        'price_per_day',
        'image',
        'description',
        'storage_location',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    public function activeBorrowingSchedules()
    {
        return $this->borrowings()
            ->whereIn('status', ['Disetujui', 'Dipinjam', 'Menunggu Verifikasi'])
            ->where('return_date', '>=', now()->toDateString())
            ->orderBy('borrow_date')
            ->get();
    }
}
