<?php

namespace App\DTOs;

class CreateTransactionDto
{
    public function __construct(
      public string $type,
      public int $amount,
    ) {
    }
}
