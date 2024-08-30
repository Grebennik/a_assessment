<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Verification\RecipientVerificationStrategy;
use PHPUnit\Framework\TestCase;

class RecipientVerificationStrategyTest extends TestCase
{
    public function testInvalidRecipient(): void
    {
        $data = [
            'data' => [
                'recipient' => [
                    'name' => '',
                    'email' => '',
                ]
            ]
        ];

        $strategy = new RecipientVerificationStrategy();
        $result = $strategy->verify($data);

        $this->assertEquals('invalid_recipient', $result);
    }

    public function testValidRecipient(): void
    {
        $data = [
            'data' => [
                'recipient' => [
                    'name' => 'Marty McFly',
                    'email' => 'marty.mcfly@gmail.com',
                ]
            ]
        ];

        $strategy = new RecipientVerificationStrategy();
        $result = $strategy->verify($data);

        $this->assertEquals('verified', $result);
    }
}
