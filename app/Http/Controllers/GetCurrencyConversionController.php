<?php

namespace App\Http\Controllers;

use App\Actions\GetCurrencyConversionAction;
use App\DTOs\GetCurrencyConversionDto;
use App\Http\Requests\GetCurrencyConversionRequest;
use Illuminate\Http\JsonResponse;

class GetCurrencyConversionController extends Controller
{
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function __invoke(
        GetCurrencyConversionRequest $request,
        GetCurrencyConversionAction $action,
    ): JsonResponse {
        $dto = $action->execute(
            dto: new GetCurrencyConversionDto(
                originCode: $request->string('currency_code.origin'),
                targetCode: $request->string('currency_code.target'),
                originAmount: $request->integer('amount'),
            )
        );

        return response()->json([
            'data' => $dto->data(),
        ]);
    }
}
