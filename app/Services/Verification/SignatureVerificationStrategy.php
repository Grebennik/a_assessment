<?php

declare(strict_types=1);

namespace App\Services\Verification;

use App\Helpers\ArrayHelper;

class SignatureVerificationStrategy implements VerificationStrategyInterface
{
    public function verify(array $data): string
    {
        $properties = ArrayHelper::flatten($data['data']);

        $hashes = array_map(
            fn($key, $value) => hash('sha256', json_encode([$key => $value], JSON_THROW_ON_ERROR)),
            array_keys($properties),
            $properties
        );

        sort($hashes);
        $calculatedHash = hash('sha256', json_encode($hashes));

        return $calculatedHash === $data['signature']['targetHash'] ? 'verified' : 'invalid_signature';
    }
}
