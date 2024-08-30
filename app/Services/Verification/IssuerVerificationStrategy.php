<?php

declare(strict_types=1);

namespace App\Services\Verification;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class IssuerVerificationStrategy implements VerificationStrategyInterface
{
    public function __construct(
        private Client $client
    ) {}

    public function verify(array $data): string
    {
        if (empty($data['data']['issuer']['name']) || empty($data['data']['issuer']['identityProof']['key']) || empty($data['data']['issuer']['identityProof']['location'])) {
            return 'invalid_issuer';
        }

        try {
            $response = Cache::remember("dns_lookup:{$data['data']['issuer']['identityProof']['location']}", 3600, function () use ($data) {
                return $this->client->get("https://dns.google/resolve?name={$data['data']['issuer']['identityProof']['location']}&type=TXT");
            });

            $dnsRecords = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            foreach ($dnsRecords['Answer'] as $record) {
                if (str_contains($record['data'], $data['data']['issuer']['identityProof']['key'])) {
                    return 'verified';
                }
            }

        } catch (GuzzleException | \JsonException $e) {
            return 'invalid_issuer';
        }

        return 'invalid_issuer';
    }
}
