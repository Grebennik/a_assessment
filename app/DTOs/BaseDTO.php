<?php

declare(strict_types=1);

namespace App\DTOs;

abstract class BaseDTO
{
    /**
     * @throws \JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    abstract public function toArray(): array;
}
