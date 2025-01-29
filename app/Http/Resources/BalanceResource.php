<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Balance */
class BalanceResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'balance' => $this->balance,

            'ledger' => new LedgerResource($this->whenLoaded('ledger')),
            'currency' => new CurrencyResource($this->whenLoaded('currency')),
        ];
    }
}
