<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Mutation extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'description',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the mutation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reference (polymorphic relation)
     */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for credit (top up) mutations
     */
    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope for debit (spending) mutations
     */
    public function scopeDebit($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if mutation is credit (income)
     */
    public function isCredit(): bool
    {
        return $this->type === 'credit';
    }

    /**
     * Check if mutation is debit (expense)
     */
    public function isDebit(): bool
    {
        return $this->type === 'debit';
    }

    /**
     * Get formatted amount with sign
     */
    public function getFormattedAmountAttribute(): string
    {
        $sign = $this->isCredit() ? '+' : '-';
        return $sign . ' Rp ' . number_format((float)$this->amount, 0, ',', '.');
    }

    /**
     * Get badge color based on type
     */
    public function getBadgeColorAttribute(): string
    {
        return $this->isCredit() ? 'emerald' : 'rose';
    }

    /**
     * Create a mutation record
     */
    public static function record(
        int $userId,
        string $type,
        float $amount,
        float $balanceBefore,
        float $balanceAfter,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
        ?array $metadata = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
            'notes' => $notes,
            'metadata' => $metadata,
        ]);
    }
}
