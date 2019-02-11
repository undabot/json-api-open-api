<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface ResourceIdentifierSchemaInterface
{
    public function getReference(): ?string;

    public function toOpenApi(): array;
}