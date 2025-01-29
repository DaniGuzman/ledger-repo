<?php

namespace App\Actions;

use App\DTOs\CreateLedgerDto;
use App\Models\Currency;
use App\Models\Ledger;

class CreateLedgerAction
{
    public function execute(Currency $currency, CreateLedgerDto $dto): Ledger
    {
        $ledger = Ledger::create([
            'name' => $dto->name,
        ]);

        $ledger->currencies()
            ->attach($currency->id);

        return $ledger;
    }

}
