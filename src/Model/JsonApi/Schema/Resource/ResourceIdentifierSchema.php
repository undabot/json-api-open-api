<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Resource;

use JsonApiOpenApi\Model\JsonApi\Schema\UuidSchema;
use JsonApiOpenApi\Model\OpenApi\ResourceIdentifierSchemaInterface;

class ResourceIdentifierSchema implements ResourceIdentifierSchemaInterface
{
    /** @var string */
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getReference(): ?string
    {
        $ref = ucwords($this->type) . 'Identifier';

        return $ref;
    }

    public function toOpenApi(): array
    {
        $uuidSchema = new UuidSchema();
        $schema = [
            'type' => 'object',
            'required' => [
                'id',
                'type',
            ],
            'properties' => [
                'id' => $uuidSchema->toOpenApi(),
                'type' => [
                    'type' => 'string',
                    'example' => $this->type,
                    'description' => $this->type,
                    'enum' => [$this->type],
                ],
            ],
        ];

        return $schema;
    }
}
