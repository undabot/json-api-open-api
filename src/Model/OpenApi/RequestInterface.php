<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface RequestInterface
{
    public function getSchemaReference(): string;
}
