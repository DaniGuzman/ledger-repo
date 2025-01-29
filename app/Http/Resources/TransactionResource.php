<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Transaction */
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
