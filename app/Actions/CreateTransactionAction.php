<?php

namespace App\Actions;

use App\DTOs\CreateTransactionDto;
use App\Models\Balance;
use App\Models\Currency;
use App\Models\Ledger;
use App\Models\LedgerCurrencies;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateTransactionAction
{
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function execute(Ledger $ledger, Currency $currency, CreateTransactionDto $dto): Transaction
    {
        return DB::transaction(function () use ($dto, $ledger, $currency) {
            if ($ledger->currencies()->where('code', $currency->code)->doesntExist()) {
                LedgerCurrencies::query()->create([
                    'ledger_id' => $ledger->id,
                    'currency_id' => $currency->id,
                ]);
            }

            $transaction = $ledger->transactions()->create([
                'transaction_id' => Str::uuid()->toString(),
                'type' => $dto->type,
                'amount' => $dto->amount,
                'currency_id' => $currency->id,
            ]);

            $ledgerBalance = Balance::firstOrCreate(
                ['ledger_id' => $ledger->id, 'currency_id' => $currency->id],
                ['balance' => 0]
            );

            if ($dto->type === 'credit') {
                $ledgerBalance->balance += $dto->amount;
            } else {
                throw_if(
                    condition: $ledgerBalance->balance < $dto->amount,
                    exception: ValidationException::withMessages(messages: [
                        'currency_code' => 'Insufficient money for this debit transaction.',
                    ]),
                );

                $ledgerBalance->balance -= $dto->amount;
            }

            $ledgerBalance->save();

            return $transaction;
        });
    }
}
