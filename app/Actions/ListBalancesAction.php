<?php

namespace App\Actions;

use App\Models\Ledger;
use Illuminate\Database\Eloquent\Collection;

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
