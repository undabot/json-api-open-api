<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Resource;

use JsonApiOpenApi\Model\JsonApi\Schema\UuidSchema;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;

class ResourceReadSchema extends AbstractResourceSchema implements ResourceSchemaInterface
{
    /** @var string */
    private $type;

    /** @var array */
    private $attributes;

    /** @var array */
    private $relationships;

    public function __construct(string $type, array $attributes, array $relationships)
    {
        $this->type = $type;
        $this->attributes = $attributes;
        $this->relationships = $relationships;
    }

    public function getReference(): ?string
    {
        $ref = ucwords($this->type) . 'ReadModel';

        return $ref;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function toOpenApi(): array
    {
        $attributes = $this->getAttributesOpenApi($this->attributes);
        $relationships = $this->getRelationshipsOpenApi($this->relationships);

        $required = [
            'id',
            'type',
        ];

        if (false === empty($attributes)) {
            $required[] = 'attributes';
        }

        if (false === empty($relationships)) {
            $required[] = 'relationships';
        }

        $schema = [
            'type' => 'object',
            'required' => $required,
            'properties' => [
                'id' => (new UuidSchema())->toOpenApi(),
                'type' => [
                    'type' => 'string',
                    'example' => $this->type,
                    'enum' => [$this->type],
                ],
            ],
        ];

        if (false === empty($attributes)) {
            $schema['properties']['attributes'] = $attributes;
        }

        if (false === empty($relationships)) {
            $schema['properties']['relationships'] = $relationships;
        }

        return $schema;
    }
}