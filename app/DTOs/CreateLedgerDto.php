<?php

namespace App\DTOs;

class CreateLedgerDto
{
    public function __construct(
      public string $name,
    ) {
    }
}
