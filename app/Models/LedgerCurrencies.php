<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LedgerCurrencies extends Model
{
    use SoftDeletes, HasFactory;

    protected function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    protected function currencies()
    {
        return $this->belongsTo(Currency::class);
    }
}
