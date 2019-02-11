<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema;

use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class UuidSchema implements SchemaInterface
{
    public function toOpenApi(): array
    {
        $schema = [
            'type' => 'string',
            'format' => 'uuid',
            'example' => 'd290f1ee-6c54-4b01-90e6-d701748f0851',
        ];

        return $schema;
    }
}
