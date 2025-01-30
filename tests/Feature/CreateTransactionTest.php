<?php

use App\Actions\CreateLedgerAction;
use App\DTOs\CreateLedgerDto;
use App\Models\Currency;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

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

it('creates a new credit transaction and creates a new balance', function () {
    assertDatabaseCount(table: 'transactions', count: 0);
    assertDatabaseCount(table: 'balances', count: 0);

    $data = [
        'type' => 'credit',
        'amount' => 500,
        'currency_code' => $this->currency->code,
    ];

    $response = postJson(
        $this->route,
        $data
    );

    $response->assertNoContent();

    assertDatabaseCount(table: 'transactions', count: 1);
    assertDatabaseCount(table: 'balances', count: 1);

    assertDatabaseHas(table: 'transactions', data: [
        'type' => $data['type'],
        'amount' => $data['amount'],
        'currency_id' => $this->currency->id,
        'ledger_id' => $this->ledger->id,
    ]);

    assertDatabaseHas(table: 'balances', data: [
        'balance' => $data['amount'],
        'currency_id' => $this->currency->id,
        'ledger_id' => $this->ledger->id,
    ]);
});

it('throws an error creating a new debit transaction', function () {
    assertDatabaseCount(table: 'transactions', count: 0);
    assertDatabaseCount(table: 'balances', count: 0);

    $data = [
        'type' => 'debit',
        'amount' => 500,
        'currency_code' => $this->currency->code,
    ];

    $response = postJson(
        $this->route,
        $data
    );

    $response->assertUnprocessable()
        ->assertJsonFragment([
            'currency_code' => ['Insufficient money for this debit transaction.'],
        ]);

    assertDatabaseCount(table: 'transactions', count: 0);
    assertDatabaseCount(table: 'balances', count: 0);
});


it('creates a new debit transaction and updates the balance', function () {
    // this http create a new transaction and balance with 5 (EUR)
    postJson($this->route, [
        'type' => 'credit',
        'amount' => 500,
        'currency_code' => $this->currency->code,
    ]);

    $data = [
        'type' => 'debit',
        'amount' => 500,
        'currency_code' => $this->currency->code,
    ];

    $response = postJson(
        $this->route,
        $data
    );

    $response->assertNoContent();

    assertDatabaseCount(table: 'transactions', count: 2);
    assertDatabaseCount(table: 'balances', count: 1);

    assertDatabaseHas(table: 'transactions', data: [
        'type' => 'debit',
        'amount' => $data['amount'],
        'currency_id' => $this->currency->id,
        'ledger_id' => $this->ledger->id,
    ]);

    assertDatabaseHas(table: 'balances', data: [
        'balance' => 0,
        'currency_id' => $this->currency->id,
        'ledger_id' => $this->ledger->id,
    ]);
});

it('throws an error creating a new debit transaction without enough balance', function () {
    // this http create a new transaction and balance with 5 (EUR)
    postJson($this->route, [
        'type' => 'credit',
        'amount' => 500,
        'currency_code' => $this->currency->code,
    ]);

    // this http will try to remove 5.01 (EUR)
    $response = postJson($this->route, [
        'type' => 'debit',
        'amount' => 501,
        'currency_code' => $this->currency->code,
    ]);

    $response->assertUnprocessable()
        ->assertJsonFragment([
            'currency_code' => ['Insufficient money for this debit transaction.'],
        ]);

    assertDatabaseCount(table: 'transactions', count: 1);
    assertDatabaseCount(table: 'balances', count: 1);
});
