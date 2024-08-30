<?php

declare(strict_types=1);

namespace App\Services\Verification;

interface VerificationContextInterface
{
    public function addStrategy(VerificationStrategyInterface $strategy): void;
    public function verify(array $data): string;
}
