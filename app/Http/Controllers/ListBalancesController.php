<?php

namespace App\Http\Controllers;

use App\Actions\ListBalancesAction;
use App\Http\Resources\BalanceResource;
use App\Models\Ledger;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class ListBalancesController extends Controller
{
    /**
     * @throws \Exception
     * @throws \Throwable
     *
     * @OA\Get(
     *     path="/api/{ledger}/balances",
     *     summary="Get the balance of a ledger",
     *     description="This endpoint retrieves the balance of a specific ledger by its ID.",
     *     @OA\Parameter(
     *         name="ledgerId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *           response="200",
     *           description="Ledger balance retrieved successfully",
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(
     *                   property="data",
     *                   ref="#/components/schemas/BalanceResponse"
     *               )
     *           )
     *       ),
     *     @OA\Response(
     *         response="404",
     *         description="Ledger not found"
     *     )
     * )
     */
    public function __invoke(
        Ledger $ledger,
        ListBalancesAction $action,
    ): JsonResource {
        return BalanceResource::collection(
            $action->execute(
                ledger: $ledger,
            )
        );
    }
}
