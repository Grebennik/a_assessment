<?php

declare(strict_types=1);

namespace App\DTOs\Verification;

use App\DTOs\BaseDTO;

class VerificationResultDTO extends BaseDTO
{
    public function __construct(
        private string $issuer,
        private string $result
    ) {}

    public function getIssuer(): string
    {
        return $this->issuer;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function toArray(): array
    {
        return [
            'issuer' => $this->getIssuer(),
            'result' => $this->getResult(),
        ];
    }
}
