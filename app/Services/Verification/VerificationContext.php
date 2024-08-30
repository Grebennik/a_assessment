<?php

declare(strict_types=1);

namespace App\Services\Verification;

class VerificationContext implements VerificationContextInterface
{
    private array $strategies = [];

    public function addStrategy(VerificationStrategyInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    public function verify(array $data): string
    {
        foreach ($this->strategies as $strategy) {
            $result = $strategy->verify($data);
            if ($result !== 'verified') {
                return $result;
            }
        }
        return 'verified';
    }
}
