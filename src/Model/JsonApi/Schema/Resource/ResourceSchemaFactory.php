<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Resource;

use JsonApiOpenApi\Annotation\Scanner\AttributeScanner;
use JsonApiOpenApi\Annotation\Scanner\RelationshipScanner;
use JsonApiOpenApi\Helper\RequestHandlerScanner;
use JsonApiOpenApi\Model\JsonApi\Schema\SchemaCollection;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;
use Undabot\JsonApi\Model\Resource\ResourceInterface;
use Undabot\SymfonyJsonApi\RequestHandler\CreateResourceRequestHandlerInterface;
use Undabot\SymfonyJsonApi\RequestHandler\UpdateResourceRequestHandlerInterface;

class ResourceSchemaFactory
{
    public function createIdentifier(ResourceInterface $resource): ResourceIdentifierSchema
    {
        return new ResourceIdentifierSchema($resource->getType());
    }

    public function createReadSchema(ResourceInterface $resource): ?ResourceSchemaInterface
    {
        $attributes = AttributeScanner::scan($resource);
        $relationships = RelationshipScanner::scan($resource);

        $schema = new ResourceReadSchema($resource->getType(), $attributes, $relationships);

        return $schema;
    }

    private function createCreateSchema(
        ResourceInterface $resource,
        ?CreateResourceRequestHandlerInterface $createResourceRequestHandler
    ): ?ResourceSchemaInterface {

        if (null === $createResourceRequestHandler) {
            return null;
        }

        $attributes = RequestHandlerScanner::getAttributes($createResourceRequestHandler);
        $relationships = RequestHandlerScanner::getRelationships($createResourceRequestHandler);

        $schema = new ResourceCreateSchema($resource->getType(), $attributes, $relationships);

        return $schema;
    }


    private function createUpdateSchema(
        ResourceInterface $resource,
        ?UpdateResourceRequestHandlerInterface $updateResourceRequestHandler
    ): ?ResourceSchemaInterface {
        if (null === $updateResourceRequestHandler) {
            return null;
        }

        $attributes = RequestHandlerScanner::getAttributes($updateResourceRequestHandler);
        $relationships = RequestHandlerScanner::getRelationships($updateResourceRequestHandler);

        $schema = new ResourceUpdateSchema($resource->getType(), $attributes, $relationships);

        return $schema;
    }

    public function createSchemaSet(
        ResourceInterface $resource,
        ?CreateResourceRequestHandlerInterface $createResourceRequestHandler = null,
        ?UpdateResourceRequestHandlerInterface $updateResourceRequestHandler = null
    ): ResourceSchemaSet {
        $schemaSet = new ResourceSchemaSet(
            $this->createIdentifier($resource),
            $this->createReadSchema($resource),
            $this->createCreateSchema($resource, $createResourceRequestHandler),
            $this->createUpdateSchema($resource, $updateResourceRequestHandler),
            );

        // Add to collection by FQCN and resource type
        SchemaCollection::add(get_class($resource), $schemaSet);
        SchemaCollection::add($resource->getType(), $schemaSet);

        return $schemaSet;
    }
}
