<?php

namespace App\Actions;

use App\DTOs\CreateLedgerDto;
use App\DTOs\CreateTransactionDto;
use App\Models\Balance;
use App\Models\Currency;
use App\Models\Ledger;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ListBalancesAction
{
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function execute(Ledger $ledger): Collection
    {
        $ledger->load([
            'balances',
            'balances.currency'
        ]);

        return $ledger->balances;
    }
}
