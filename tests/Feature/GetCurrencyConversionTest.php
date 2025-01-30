t<?php

use App\Actions\CreateLedgerAction;
use App\Actions\CreateTransactionAction;
use App\DTOs\CreateLedgerDto;
use App\DTOs\CreateTransactionDto;
use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\getJson;

beforeEach(function () {
   $this->route = route('conversion');
});

it('responds unprocessable if validation fails', function (array $queryParams, array $messages) {
    $response =  getJson($this->route . '?' . http_build_query($queryParams));

    $response->assertUnprocessable()
        ->assertJsonFragment($messages);
})->with([
    'no body content' => [
        'queryParams' => [],
        'messages' => [
            'currency_code' => ['The currency code field is required.'],
            'currency_code.origin' => ['The currency code.origin field is required.'],
            'currency_code.target' => ['The currency code.target field is required.'],
            'amount' => ['The amount field is required.'],
        ],
    ],
    'min amount and different target code' => [
        'queryParams' => [
            'currency_code' => [
                'origin' => 'EUR',
                'target' => 'EUR',
            ],
            'amount' => 99,
        ],
        'messages' => [
            'currency_code.target' => ['The currency code.target field and currency code.origin must be different.'],
            'amount' => ['The amount field must be at least 100.'],
        ],
    ],
]);

it('converts returns the conversion when calling a third party API', function () {
    $fakeValue = 9000;

    Http::fake([
        '*' => Http::response([
            'data' => [
                'conversion' => [
                    'value' => $fakeValue,
                ]
            ]
        ]),
    ]);

    $queryParams = [
        'currency_code' => [
            'origin' => 'EUR',
            'target' => 'USD',
        ],
        'amount' => 1000,
    ];

    $response = getJson($this->route . '?' . http_build_query($queryParams));

   $response->assertOk();

   expect($response->json('data'))
       ->toBe([
           $queryParams['currency_code']['origin'] => (string) $queryParams['amount'],
           $queryParams['currency_code']['target'] => (string) $fakeValue,
       ]);
});

it('throws an error when the third party API crashes', function () {
    Http::fake([
        '*' => Http::response([
            'message' => 'Currency not found',
        ], 404),
    ]);

    $queryParams = [
        'currency_code' => [
            'origin' => 'EUR',
            'target' => 'USD',
        ],
        'amount' => 1000,
    ];

    $response = getJson($this->route . '?' . http_build_query($queryParams));

    $response->assertServiceUnavailable()
        ->assertJsonFragment([
            'server' => ['Unavailable service.'],
        ]);
});
