<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\LedgerCurrencies */
class LedgerCurrenciesResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,

            'ledger' => new LedgerResource($this->whenLoaded('ledger')),
            'currencies' => new CurrencyResource($this->whenLoaded('currencies')),
        ];
    }
}
