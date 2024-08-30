<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Verification;

use App\DTOs\Verification\VerificationResultDTO;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\VerificationRequest;
use App\Models\Verification;
use App\Services\Verification\VerificationContextInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VerificationController extends BaseApiController
{
    public function __construct(
        private VerificationContextInterface $verificationContext
    ) {}

    public function verify(VerificationRequest $request): JsonResponse
    {
        try {
            $fileContent = json_decode($request->file('file')->get(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->errorResponse('Invalid JSON format', 400);
        }

        if (!isset($fileContent['data']['issuer'])) {
            return $this->errorResponse('Issuer information is missing', 400);
        }

        $this->verificationContext->addStrategy(app(\App\Services\Verification\RecipientVerificationStrategy::class));
        $this->verificationContext->addStrategy(app(\App\Services\Verification\IssuerVerificationStrategy::class));
        $this->verificationContext->addStrategy(app(\App\Services\Verification\SignatureVerificationStrategy::class));

        $result = $this->verificationContext->verify($fileContent);

        Verification::create([
            'user_id' => Auth::id(),
            'file_type' => 'json',
            'result' => $result,
        ]);

        $dto = new VerificationResultDTO($fileContent['data']['issuer']['name'], $result);

        return $this->successResponse($dto->toArray(), 200);
    }
}
