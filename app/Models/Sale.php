<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Sale extends Model
{
    protected $fillable = [
        'code',
        'sale_date',
        'total_amount',
        'status',
        'remaining_amount',
        'customer_name',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function updateRemainingAmount()
    {
        $this->remaining_amount = $this->total_amount - $this->total_paid;
        $this->status = $this->remaining_amount <= 0 ? 'Sudah Dibayar' :
            ($this->total_paid > 0 ? 'Belum Dibayar Sepenuhnya' : 'Belum Dibayar');
        $this->save();
    }

    public static function generateCode()
    {
        $date = Carbon::now()->format('Ymd');
        $lastSale = static::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastSale ? (int) substr($lastSale->code, -4) + 1 : 1;

        return 'SL' . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
