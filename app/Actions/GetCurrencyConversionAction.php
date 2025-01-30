<?php

namespace App\Actions;

use App\DTOs\GetCurrencyConversionDto;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GetCurrencyConversionAction
{
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function execute(GetCurrencyConversionDto $dto): GetCurrencyConversionDto
    {
        Http::fake([
            '*' => Http::response([
                'data' => [
                    'conversion' => [
                        'value' => 9000,
                    ]
                ]
            ]),
        ]);

        $response = Http::baseUrl('random.third.party.com')
            ->acceptJson()
            ->get('/conversion', [
                'origin' => $dto->originCode,
                'target' => $dto->targetCode,
                'amount' => $dto->originAmount,
            ]);

        $response->onError(function (Response $response) {
            Log::error(
                message: 'Error calling third party conversion',
                context: [
                    'message' => $response->json('message'),
                    'error' => [
                        'code' => $response->getStatusCode(),
                    ],
                ],
            );

            throw ValidationException::withMessages(messages: [
                'server' => 'Unavailable service.',
            ])
                ->status(503);
        });

        $dto->targetAmount = $response->json('data.conversion.value');

        return $dto;
    }
}
