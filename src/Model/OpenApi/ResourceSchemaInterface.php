<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface ResourceSchemaInterface
{
    public function getReference(): ?string;

    public function getType(): string;

    public function toOpenApi(): array;
}