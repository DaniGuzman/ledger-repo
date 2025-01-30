<?php

namespace App\Http\Controllers;

use App\Actions\CreateLedgerAction;
use App\Actions\CreateTransactionAction;
use App\DTOs\CreateLedgerDto;
use App\DTOs\CreateTransactionDto;
use App\Http\Requests\CreateLedgerRequest;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Resources\LedgerResource;
use App\Http\Resources\TransactionResource;
use App\Models\Currency;
use App\Models\Ledger;
use App\Models\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateTransactionController extends Controller
{
    /**
     * @throws \Exception
     */
    public function __invoke(
        CreateTransactionRequest $request,
        Ledger $ledger,
        CreateTransactionAction $action,
    ): JsonResource {
        $currency = Currency::query()
            ->where('code', $request->currency_code)
            ->firstOrFail();

        return TransactionResource::make(
            $action->execute(
                ledger: $ledger,
                currency: $currency,
                dto: new CreateTransactionDto(
                    type: $request->type,
                    amount: $request->integer('amount'),
                )
            )
        );
    }
}
