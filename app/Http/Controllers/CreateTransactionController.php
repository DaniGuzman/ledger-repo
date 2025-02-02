<?php

namespace App\Http\Controllers;

use App\Actions\CreateTransactionAction;
use App\DTOs\CreateTransactionDto;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Currency;
use App\Models\Ledger;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class CreateTransactionController extends Controller
{
    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @OA\Post(
     *     path="/ledgers/{ledger}/transactions",
     *     tags={"Ledgers"},
     *     summary="Create a new transaction",
     *     description="This endpoint allows you to create a new transaction in a ledger.",
     *     @OA\Parameter(
     *          name="ledger",
     *          in="path",
     *          required=true,
     *          description="The ID of the ledger where the transaction will be created.",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Transaction details",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"type", "amount", "currency_code"},
     *             @OA\Property(property="type", type="string", example="credit"),
     *             @OA\Property(property="amount", type="integer", example=100, description="The amount is always cents"),
     *             @OA\Property(property="currency_code", type="string", example="USD")
     *         )
     *     ),
     *     @OA\Response(
     *           response="201",
     *           description="Transaction created successfully",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/TransactionResponse"
     *               )
     *           )
     *       ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request data"
     *     )
     * )
     */
    public function __invoke(
        CreateTransactionRequest $request,
        Ledger $ledger,
        CreateTransactionAction $action,
    ): JsonResource {
        $currency = Currency::query()
            ->where('code', $request->currency_code)
            ->firstOrFail();

        $transaction = $action->execute(
            ledger: $ledger,
            currency: $currency,
            dto: new CreateTransactionDto(
                type: $request->type,
                amount: $request->integer('amount'),
            ),
        );

        return TransactionResource::make(
            $transaction
        );
    }
}
