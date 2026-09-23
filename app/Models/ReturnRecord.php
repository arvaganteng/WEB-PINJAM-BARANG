<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRecord extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'return_code',
        'borrowing_id',
        'return_date',
        'item_condition',
        'fine_amount',
        'fine_payment_method',
        'fine_payment_proof',
        'fine_payment_status',
        'fine_payment_notes',
        'admin_notes',
        'customer_notes',
        'return_photo',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
            'verified_at' => 'datetime',
            'fine_amount' => 'decimal:2',
        ];
    }

    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }
    
    public function isPending(): bool
    {
        return is_null($this->verified_at);
    }

    public function isFinePaid(): bool
    {
        return $this->fine_payment_status === 'Lunas';
    }

    public function isFinePendingVerification(): bool
    {
        return $this->fine_payment_status === 'Menunggu Verifikasi';
    }
}
