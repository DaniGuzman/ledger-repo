<?php

namespace App\Http\Controllers;

use App\Actions\CreateLedgerAction;
use App\DTOs\CreateLedgerDto;
use App\Http\Requests\CreateLedgerRequest;
use App\Http\Resources\LedgerResource;
use App\Models\Currency;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class CreateLedgerController extends Controller
{
    /**
     *
     * @OA\OpenApi(
     *      @OA\Info(
     *          title="LEDGERS TASK - GUZMAN",
     *          version="1.0.0",
     *          description="This is the API documentation for my Task.",
     *          @OA\License(
     *              name="MIT",
     *              url="https://opensource.org/licenses/MIT"
     *          )
     *      ),
     *      @OA\Server(
     *          url="http://localhost:7877/api/",
     *          description="Main API server"
     *      )
     *  )
     *
     * @OA\Post(
     *       path="/ledgers",
     *       tags={"Ledgers"},
     *       summary="Create a new ledger",
     *       description="This endpoint allows you to create a new ledger.",
     *       @OA\RequestBody(
     *           required=true,
     *           description="Ledger information",
     *           @OA\JsonContent(
     *               type="object",
     *               required={"name", "currency_code"},
     *               @OA\Property(property="name", type="string", example="My first ledger"),
     *               @OA\Property(property="currency_code", type="string", example="USD")
     *           )
     *       ),
     *     @OA\Response(
     *          response="201",
     *          description="Ledger created successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/LedgerResponse"
     *              )
     *          )
     *      ),
     *       @OA\Response(
     *           response="400",
     *           description="Invalid request data"
     *       )
     *   )
     *
     * @param CreateLedgerRequest $request
     * @param CreateLedgerAction $action
     * @return JsonResource
     *
     */
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
