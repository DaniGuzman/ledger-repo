<?php

use App\Models\Currency;
use App\Models\Ledger;
use function Pest\Laravel\postJson;

beforeEach(function () {
   $this->currency = Currency::factory()->create([
       'code' => 'EUR',
   ]);

   $this->route = route('ledgers.create');
});

it('responds unprocessable if validation fails', function (array $body, array $messages) {
    $response = postJson(
        $this->route,
        $body
    );

    $response->assertUnprocessable()
        ->assertJsonFragment($messages);
})->with([
    'no body content' => [
        'body' => [],
        'messages' => [
            'name' => ['The name field is required.'],
            'currency_code' => ['The currency code field is required.']
        ],
    ],
    'bad types' => [
        'body' => [
            'name' => ['__random_name__'],
            'currency_code' => ['__random_currency_code__'],
        ],
        'messages' => [
            'name' => ['The name field must be a string.'],
            'currency_code' => ['The currency code field must be a string.']
        ],
    ],
    'currency not found' => [
        'body' => [
            'name' => '__random_name__',
            'currency_code' => '__random_currency_code__',
        ],
        'messages' => [
            'currency_code' => ['The selected currency code is invalid.']
        ],
    ],
]);

it('creates a new ledger with a unique identifier and initial currency setting', function () {
    $data = [
        'name' => '__dummy_first_ledger__',
        'currency_code' => $this->currency->code,
    ];

    $response = postJson(
        $this->route,
        $data
    );

    $response->assertCreated();

    $ledger = Ledger::find($response->json('data.id'));

    expect($response->json('data.name'))
        ->toBe($data['name'])
        ->and($ledger->currencies)
        ->toHaveCount(1)
        ->and($ledger->currencies->first()->id)
        ->toBe($this->currency->id);
});
