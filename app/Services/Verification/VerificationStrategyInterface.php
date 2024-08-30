<?php

declare(strict_types=1);

namespace App\Services\Verification;

interface VerificationStrategyInterface
{
    public function verify(array $data): string;
}
