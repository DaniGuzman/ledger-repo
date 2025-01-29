<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Currency */
class CurrencyResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}
