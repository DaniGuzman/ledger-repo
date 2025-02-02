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
     *
     * @OA\Post(
     *     path="/api/convert",
     *     summary="Convert currency from one to another",
     *     description="This endpoint allows you to convert an amount from one currency to another.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Currency conversion details",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"currency_code", "amount"},
     *             @OA\Property(
     *                 property="currency_code",
     *                 type="object",
     *                 required={"origin", "target"},
     *                 @OA\Property(property="origin", type="string", example="USD"),
     *                 @OA\Property(property="target", type="string", example="EUR")
     *             ),
     *             @OA\Property(property="amount", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Currency converted successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="converted_amount", type="integer", example=85),
     *                  @OA\Property(property="currency_code", type="object",
     *                      @OA\Property(property="origin", type="string", example="USD"),
     *                      @OA\Property(property="target", type="string", example="EUR")
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request data"
     *     )
     * )
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
