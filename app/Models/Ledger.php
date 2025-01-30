<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ledger extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
    ];

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'ledger_currencies');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
