<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Response;

use JsonApiOpenApi\Helper\SchemaReferenceGenerator;
use JsonApiOpenApi\Model\JsonApi\Schema\SchemaCollection;

trait IncludableResponseTrait
{
    public function generateIncludedResponseProperty(array $responseContentSchema, array $includes): array
    {
        $includedSchemas = [];
        foreach ($includes as $key => $class) {
            $referencedClass = SchemaCollection::get($class);
            $includedSchemas[] = ['$ref' => SchemaReferenceGenerator::ref($referencedClass->getReadModel()->getReference())];
        }

        if (false === empty($includedSchemas)) {
            $responseContentSchema['schema']['properties']['included'] = [
                'type' => 'array',
                'items' => [
                    'anyOf' => $includedSchemas,
                ],
            ];
        }

        return $responseContentSchema;
    }
}
