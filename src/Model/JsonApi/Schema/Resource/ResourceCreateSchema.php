<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Resource;

use JsonApiOpenApi\Model\JsonApi\Schema\AttributeSchema;
use JsonApiOpenApi\Model\JsonApi\Schema\RelationshipSchema;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;

class ResourceCreateSchema extends AbstractResourceSchema implements ResourceSchemaInterface
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
        $ref = ucwords($this->type) . 'CreateModel';

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
            'type',
        ];

        $requiredAttributes = array_filter($this->attributes, function (AttributeSchema $attributeSchema) {
            return $attributeSchema->isRequired();
        });

        $requiredRelationships = array_filter($this->relationships, function (RelationshipSchema $relationshipSchema) {
            return $relationshipSchema->isRequired();
        });

        if (false === empty($requiredAttributes)) {
            $required[] = 'attributes';
        }

        if (false === empty($requiredRelationships)) {
            $required[] = 'relationships';
        }

        $schema = [
            'type' => 'object',
            'required' => $required,
            'properties' => [
                'type' => [
                    'type' => 'string',
                    'example' => $this->type,
                    'enum' => [$this->type],
                ],
                'attributes' => $attributes,
                'relationships' => $relationships,
            ],
        ];

        return $schema;
    }
}
