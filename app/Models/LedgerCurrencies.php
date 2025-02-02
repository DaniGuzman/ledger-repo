<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LedgerCurrencies extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'ledger_id',
        'currency_id',
    ];

    protected function ledger(): BelongsTo
    {
        return $this->belongsTo(Ledger::class);
    }

    protected function currencies(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
