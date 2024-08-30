<?php

declare(strict_types=1);

namespace App\Services\Verification;

class RecipientVerificationStrategy implements VerificationStrategyInterface
{
    public function verify(array $data): string
    {
        if (empty($data['data']['recipient']['name']) || empty($data['data']['recipient']['email'])) {
            return 'invalid_recipient';
        }
        return 'verified';
    }
}
