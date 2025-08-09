<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Payment extends Model
{
    protected $fillable = [
        'code',
        'sale_id',
        'payment_date',
        'amount',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public static function generateCode()
    {
        $date = Carbon::now()->format('Ymd');
        $lastPayment = static::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastPayment ? (int) substr($lastPayment->code, -4) + 1 : 1;

        return 'PY' . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
