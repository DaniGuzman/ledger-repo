<?php

use App\Actions\CreateLedgerAction;
use App\DTOs\CreateLedgerDto;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;
use \App\Models\Currency;

beforeEach(function () {
   $this->currency = Currency::factory()->create([
       'code' => 'EUR',
   ]);

   $this->ledger = app(CreateLedgerAction::class)->execute(
       currency: $this->currency,
       dto: new CreateLedgerDto(
           name: '__dummy_name',
       )
   );

   $this->route = route('ledgers.transactions.create', [
       'ledger' => $this->ledger,
   ]);
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
            'type' => ['The type field is required.'],
            'amount' => ['The amount field is required.'],
            'currency_code' => ['The currency code field is required.']
        ],
    ],
    'bad types' => [
        'body' => [
            'type' => ['__random_type__'],
            'amount' => ['__random_amount__'],
            'currency_code' => ['__random_currency_code__'],
        ],
        'messages' => [
            'type' => ['The selected type is invalid.', 'The type field must be a string.'],
            'amount' => ['The amount field must be a number.'],
            'currency_code' => ['The currency code field must be a string.']
        ],
    ],
    'invalid type | invalid amount | currency not found' => [
        'body' => [
            'type' => '__random_type__',
            'amount' => 0,
            'currency_code' => '__random_currency_code__',
        ],
        'messages' => [
            'type' => ['The selected type is invalid.'],
            'amount' => ['The amount field must be at least 1.'],
            'currency_code' => ['The selected currency code is invalid.'],
        ],
    ],
]);

it('it throws an error because the ledger doesnt have the current currency attached', function () {
    $newCurrency = Currency::factory()->create(['code' => 'USD']);

    $data = [
        'type' => 'debit',
        'amount' => 500,
        'currency_code' => $newCurrency->code,
    ];

    $response = postJson(
        $this->route,
        $data
    );

    expect($response->json('message'))
        ->toBe('This Ledger doesnt support this currency.');

    assertDatabaseCount(table: 'transactions', count: 0);
    assertDatabaseCount(table: 'balances', count: 0);
});

it('creates a new credit transaction and attaches the balance to it', function () {
    $data = [
        'type' => 'credit',
        'amount' => 500,
        'currency_code' => $this->currency->code,
    ];

    $response = postJson(
        $this->route,
        $data
    );

    dd($response->json('data'));

    expect($response->json('message'))
        ->toBe('This Ledger doesnt support this currency.');

    assertDatabaseCount(table: 'transactions', count: 0);
    assertDatabaseCount(table: 'balances', count: 0);
});
