<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    public function testVerificationEndpoint(): void
    {
        $user = User::factory()->create();

        $fileContent = [
            'data' => [
                'id' => '63c79bd9303530645d1cca00',
                'name' => 'Certificate of Completion',
                'recipient' => [
                    'name' => 'Marty McFly',
                    'email' => 'marty.mcfly@gmail.com'
                ],
                'issuer' => [
                    'name' => 'Accredify',
                    'identityProof' => [
                        'type' => 'DNS-DID',
                        'key' => 'did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller',
                        'location' => 'ropstore.accredify.io'
                    ]
                ],
                'issued' => '2022-12-23T00:00:00+08:00'
            ],
            'signature' => [
                'type' => 'SHA3MerkleProof',
                'targetHash' => '288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e'
            ]
        ];

        $file = UploadedFile::fake()->createWithContent('test.json', json_encode($fileContent, JSON_THROW_ON_ERROR));

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/verify', ['file' => $file]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'issuer' => 'Accredify',
                    'result' => 'verified',
                ]
            ]);
    }

    public function testInvalidRecipient(): void
    {
        $user = User::factory()->create();

        $fileContent = [
            'data' => [
                'id' => '63c79bd9303530645d1cca00',
                'name' => 'Certificate of Completion',
                'recipient' => [
                    'name' => '',
                    'email' => ''
                ],
                'issuer' => [
                    'name' => 'Accredify',
                    'identityProof' => [
                        'type' => 'DNS-DID',
                        'key' => 'did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller',
                        'location' => 'ropstore.accredify.io'
                    ]
                ],
                'issued' => '2022-12-23T00:00:00+08:00'
            ],
            'signature' => [
                'type' => 'SHA3MerkleProof',
                'targetHash' => '288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e'
            ]
        ];

        $file = UploadedFile::fake()->createWithContent('test.json', json_encode($fileContent, JSON_THROW_ON_ERROR));

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/verify', ['file' => $file]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'result' => 'invalid_recipient',
                ]
            ]);
    }

    public function testInvalidIssuer(): void
    {
        $user = User::factory()->create();

        $fileContent = [
            'data' => [
                'id' => '63c79bd9303530645d1cca00',
                'name' => 'Certificate of Completion',
                'recipient' => [
                    'name' => 'Marty McFly',
                    'email' => 'marty.mcfly@gmail.com'
                ],
                'issuer' => [
                    'name' => '',
                    'identityProof' => [
                        'type' => 'DNS-DID',
                        'key' => '',
                        'location' => ''
                    ]
                ],
                'issued' => '2022-12-23T00:00:00+08:00'
            ],
            'signature' => [
                'type' => 'SHA3MerkleProof',
                'targetHash' => '288f94aadadf486cfdad84b9f4305f7d51eac62db18376d48180cc1dd2047a0e'
            ]
        ];

        $file = UploadedFile::fake()->createWithContent('test.json', json_encode($fileContent, JSON_THROW_ON_ERROR));

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/verify', ['file' => $file]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'result' => 'invalid_issuer',
                ]
            ]);
    }

    public function testInvalidSignature(): void
    {
        $user = User::factory()->create();

        $fileContent = [
            'data' => [
                'id' => '63c79bd9303530645d1cca00',
                'name' => 'Certificate of Completion',
                'recipient' => [
                    'name' => 'Marty McFly',
                    'email' => 'marty.mcfly@gmail.com'
                ],
                'issuer' => [
                    'name' => 'Accredify',
                    'identityProof' => [
                        'type' => 'DNS-DID',
                        'key' => 'did:ethr:0x05b642ff12a4ae545357d82ba4f786f3aed84214#controller',
                        'location' => 'ropstore.accredify.io'
                    ]
                ],
                'issued' => '2022-12-23T00:00:00+08:00'
            ],
            'signature' => [
                'type' => 'SHA3MerkleProof',
                'targetHash' => 'invalidhash'
            ]
        ];

        $file = UploadedFile::fake()->createWithContent('test.json', json_encode($fileContent, JSON_THROW_ON_ERROR));

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/verify', ['file' => $file]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'result' => 'invalid_signature',
                ]
            ]);
    }

    public function testValidationErrors(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/verify', ['file' => null]);

        $response->assertStatus(422)
            ->assertJsonStructure(['error']);
    }
}
