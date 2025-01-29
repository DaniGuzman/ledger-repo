<?php

namespace App\Http\Controllers;

use App\Actions\CreateLedgerAction;
use App\DTOs\CreateLedgerDto;
use App\Http\Requests\CreateLedgerRequest;
use App\Http\Resources\LedgerResource;
use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateLedgerController extends Controller
{
    public function __invoke(CreateLedgerRequest $request, CreateLedgerAction $action): JsonResource
    {
        $currency = Currency::query()
            ->where('code', $request->currency_code)
            ->firstOrFail();

        return LedgerResource::make(
            $action->execute(
                currency: $currency,
                dto: new CreateLedgerDto(
                    name: $request->name,
                )
            )
        );
    }
}
