t<?php

use App\Actions\CreateLedgerAction;
use App\Actions\CreateTransactionAction;
use App\DTOs\CreateLedgerDto;
use App\DTOs\CreateTransactionDto;
use App\Models\Currency;
use function Pest\Laravel\getJson;

beforeEach(function () {
   $this->eurCurrency = Currency::factory()->create([
       'code' => 'EUR',
   ]);

   $this->ledger = app(CreateLedgerAction::class)->execute(
       currency: $this->eurCurrency,
       dto: new CreateLedgerDto(
           name: '__dummy_name',
       )
   );

   $this->eurTransaction = app(CreateTransactionAction::class)->execute(
       ledger: $this->ledger,
       currency: $this->eurCurrency,
       dto: new CreateTransactionDto(
           type: 'credit',
           amount: 9000,
       )
   );

    $this->usdCurrency = Currency::factory()->create([
        'code' => 'USD',
    ]);

    $this->ledger->currencies()->attach($this->usdCurrency->id);

    $this->usdTransaction = app(CreateTransactionAction::class)->execute(
        ledger: $this->ledger,
        currency: $this->usdCurrency,
        dto: new CreateTransactionDto(
            type: 'credit',
            amount: 3000,
        )
    );

   $this->balances = collect([
       $this->eurTransaction,
       $this->usdTransaction,
   ]);

   $this->route = route('ledgers.balances.index', [
       'ledger' => $this->ledger,
   ]);
});

it('returns all the balances of a specified ledger', function () {
   $response = getJson($this->route);

   $response->assertOk();

   expect($response->json('data.*.balance'))
    ->toHaveCount(2)
    ->toBe([
        $this->eurTransaction->amount,
        $this->usdTransaction->amount,
    ])
   ->and($response->json('data.*.currency.code'))
   ->toBe(['EUR', 'USD']);
});

it('returns all the balances of a specified ledger with updated balance', function () {
    $dto = new CreateTransactionDto(
        type: 'debit',
        amount: 5000,
    );

    app(CreateTransactionAction::class)->execute(
        ledger: $this->ledger,
        currency: $this->eurCurrency,
        dto: $dto,
    );

    $response = getJson($this->route)->assertOk();

    expect($response->json('data.*.balance'))
        ->toHaveCount(2)
        ->toBe([
            $this->usdTransaction->amount,
            $this->eurTransaction->amount - $dto->amount,
        ])
        ->and($response->json('data.*.currency.code'))
        ->toBe(['USD', 'EUR']);
});
