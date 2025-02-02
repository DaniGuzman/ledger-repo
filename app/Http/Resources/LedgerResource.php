<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="LedgerResponse",
 *     type="object",
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-02-02T14:30:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-02-02T15:00:00Z"),
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="My first ledger"),
 * ),
 *
 * @mixin \App\Models\Ledger
 */
class LedgerResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
