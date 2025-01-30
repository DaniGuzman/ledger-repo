<?php

namespace App\DTOs;

class GetCurrencyConversionDto
{
    public function __construct(
        public string $originCode,
        public string $targetCode,
        public string $originAmount,
        public string|null $targetAmount = null,
    ) {
    }

    public function data(): array
    {
        return [
            $this->originCode => $this->originAmount,
            $this->targetCode => $this->targetAmount,
        ];
    }
}
