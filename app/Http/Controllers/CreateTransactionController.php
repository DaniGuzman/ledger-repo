<?php

namespace App\Http\Controllers;

use App\Actions\CreateTransactionAction;
use App\DTOs\CreateTransactionDto;
use App\Http\Requests\CreateTransactionRequest;
use App\Models\Currency;
use App\Models\Ledger;
use Illuminate\Http\Response;

class CreateTransactionController extends Controller
{
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function __invoke(
        CreateTransactionRequest $request,
        Ledger $ledger,
        CreateTransactionAction $action,
    ): Response {
        $currency = Currency::query()
            ->where('code', $request->currency_code)
            ->firstOrFail();

        $action->execute(
            ledger: $ledger,
            currency: $currency,
            dto: new CreateTransactionDto(
                type: $request->type,
                amount: $request->integer('amount'),
            ),
        );

        return response()->noContent();
    }
}
