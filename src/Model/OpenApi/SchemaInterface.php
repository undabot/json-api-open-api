<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface SchemaInterface
{
    public function toOpenApi(): array;
}
