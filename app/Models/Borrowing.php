<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_code',
        'user_id',
        'item_id',
        'quantity',
        'borrow_date',
        'return_date',
        'extension_date',
        'duration_days',
        'total_price',
        'purpose',
        'location',
        'latitude',
        'longitude',
        'notes',
        'id_card_image',
        'extension_reason',
        'status',
        'extension_status',
        'rejection_reason',
        'payment_method',
        'payment_proof',
        'payment_status',
        'paid_at',
        'payment_notes',
    ];

    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',
            'return_date' => 'date',
            'extension_date' => 'date',
            'paid_at' => 'datetime',
            'total_price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function returnRecord(): HasOne
    {
        return $this->hasOne(ReturnRecord::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
    
    // Helper method untuk cek apakah bisa dikembalikan
    public function canBeReturned(): bool
    {
        return $this->status === 'Dipinjam' && !$this->returnRecord;
    }

    // Helper method untuk cek apakah bisa perpanjang
    public function canRequestExtension(): bool
    {
        return $this->status === 'Dipinjam' && in_array($this->extension_status, ['None', 'Rejected']) && !$this->returnRecord;
    }
    
    // Helper method untuk cek apakah sudah dikembalikan
    public function isReturned(): bool
    {
        return $this->returnRecord && $this->returnRecord->isVerified();
    }
}
