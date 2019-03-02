<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface ServerInterface
{
    public function toOpenApi(): array;
}
