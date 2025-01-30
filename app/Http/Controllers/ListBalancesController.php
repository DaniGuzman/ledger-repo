<?php

namespace App\Http\Controllers;

use App\Actions\ListBalancesAction;
use App\Http\Resources\BalanceResource;
use App\Models\Ledger;
use Illuminate\Http\Resources\Json\JsonResource;

class ListBalancesController extends Controller
{
    /**
     * @throws \Exception
     * @throws \Throwable
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
