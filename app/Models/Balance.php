<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Balance extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'balance',
        'ledger_id',
        'currency_id',
    ];

    protected function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    protected function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
