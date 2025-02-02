<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      schema="TransactionResponse",
 *      type="object",
 *      @OA\Property(property="created_at", type="string", format="date-time", example="2025-02-02T14:30:00Z"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-02T15:00:00Z"),
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="transaction_id", type="string", example="TX123"),
 *      @OA\Property(property="type", type="string", example="credit"),
 *      @OA\Property(property="amount", type="number", format="float", example=100.00)
 *  )
 * /
 *
 * @mixin \App\Models\Transaction
 */

class TransactionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'transaction_id' => $this->transaction_id,
            'type' => $this->type,
            'amount' => $this->amount,

            'ledger' => new LedgerResource($this->whenLoaded('ledger')),
            'currency' => new CurrencyResource($this->whenLoaded('currency')),
        ];
    }
}
