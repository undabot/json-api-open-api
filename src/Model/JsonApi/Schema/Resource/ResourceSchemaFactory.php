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
    public function createIdentifier(string $type): ResourceIdentifierSchema
    {
        return new ResourceIdentifierSchema($type);
    }

    public function createReadSchema(string $resourceClass, string $type): ?ResourceSchemaInterface
    {
        $attributes = AttributeScanner::scan($resourceClass);
        $relationships = RelationshipScanner::scan($resourceClass);

        $schema = new ResourceReadSchema($type, $attributes, $relationships);

        return $schema;
    }

    private function createCreateSchema(
        string $type,
        ?CreateResourceRequestHandlerInterface $createResourceRequestHandler
    ): ?ResourceSchemaInterface {

        if (null === $createResourceRequestHandler) {
            return null;
        }

        $attributes = RequestHandlerScanner::getAttributes($createResourceRequestHandler);
        $relationships = RequestHandlerScanner::getRelationships($createResourceRequestHandler);

        $schema = new ResourceCreateSchema($type, $attributes, $relationships);

        return $schema;
    }


    private function createUpdateSchema(
        string $type,
        ?UpdateResourceRequestHandlerInterface $updateResourceRequestHandler
    ): ?ResourceSchemaInterface {
        if (null === $updateResourceRequestHandler) {
            return null;
        }

        $attributes = RequestHandlerScanner::getAttributes($updateResourceRequestHandler);
        $relationships = RequestHandlerScanner::getRelationships($updateResourceRequestHandler);

        $schema = new ResourceUpdateSchema($type, $attributes, $relationships);

        return $schema;
    }

    public function createSchemaSet(
        string $resourceClass,
        ?CreateResourceRequestHandlerInterface $createResourceRequestHandler = null,
        ?UpdateResourceRequestHandlerInterface $updateResourceRequestHandler = null
    ): ResourceSchemaSet {

        // @todo is there better way to use the const
        $type = $resourceClass::TYPE;

        $schemaSet = new ResourceSchemaSet(
            $this->createIdentifier($type),
            $this->createReadSchema($resourceClass, $type),
            $this->createCreateSchema($type, $createResourceRequestHandler),
            $this->createUpdateSchema($type, $updateResourceRequestHandler),
            );

        // Add to collection by FQCN and resource type
        SchemaCollection::add($resourceClass, $schemaSet);
        SchemaCollection::add($type, $schemaSet);

        return $schemaSet;
    }
}
