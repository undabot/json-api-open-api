<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Resource;

use JsonApiOpenApi\Model\JsonApi\Schema\AttributeSchema;
use JsonApiOpenApi\Model\JsonApi\Schema\RelationshipSchema;

abstract class AbstractResourceSchema
{
    protected function getAttributesOpenApi(array $attributeSchemas): array
    {
        $attributes = [];

        /** @var AttributeSchema $attributeSchema */
        foreach ($attributeSchemas as $attributeSchema) {
            $attributes[$attributeSchema->getTitle()] = $attributeSchema->toOpenApi();
        }

        return [
            'type' => 'object',
            'nullable' => false,
            'required' => array_keys(array_filter($attributeSchemas, function (AttributeSchema $attributeSchema) {
                return $attributeSchema->isRequired();
            })),
            'properties' => $attributes,
        ];
    }

    protected function getRelationshipsOpenApi(array $relationshipSchemas)
    {
        $relationships = [];

        /** @var RelationshipSchema $relationshipSchema */
        foreach ($relationshipSchemas as $relationshipSchema) {
            $relationships[$relationshipSchema->getName()] = $relationshipSchema->toOpenApi();
        }

        return [
            'type' => 'object',
            'nullable' => false,
            'required' => array_keys(array_filter($relationshipSchemas, function (RelationshipSchema $relationshipSchema) {
                return $relationshipSchema->isRequired();
            })),
            'properties' => $relationships,
        ];
    }
}
