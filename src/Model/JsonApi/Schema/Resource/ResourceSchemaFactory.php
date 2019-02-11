<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Resource;

use JsonApiOpenApi\Annotation\Scanner\AttributeScanner;
use JsonApiOpenApi\Annotation\Scanner\RelationshipScanner;
use JsonApiOpenApi\Helper\RequestHandlerScanner;
use JsonApiOpenApi\Model\JsonApi\Schema\SchemaCollection;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

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

    /**
     * @throws ReflectionException
     */
    private function createCreateSchema(
        string $type,
        ?string $createResourceRequestHandlerClass = null
    ): ?ResourceSchemaInterface {
        if (null === $createResourceRequestHandlerClass) {
            return null;
        }

        $attributes = RequestHandlerScanner::getAttributes($createResourceRequestHandlerClass);
        $relationships = RequestHandlerScanner::getRelationships($createResourceRequestHandlerClass);

        $schema = new ResourceCreateSchema($type, $attributes, $relationships);

        return $schema;
    }

    /**
     * @throws ReflectionException
     */
    private function createUpdateSchema(
        string $type,
        ?string $updateResourceRequestHandlerClass = null
    ): ?ResourceSchemaInterface {
        if (null === $updateResourceRequestHandlerClass) {
            return null;
        }

        $attributes = RequestHandlerScanner::getAttributes($updateResourceRequestHandlerClass);
        $relationships = RequestHandlerScanner::getRelationships($updateResourceRequestHandlerClass);

        $schema = new ResourceUpdateSchema($type, $attributes, $relationships);

        return $schema;
    }

    /**
     * @throws ReflectionException
     */
    public function createSchemaSet(
        string $resourceClass,
        ?string $createResourceRequestHandlerClass = null,
        ?string $updateResourceRequestHandlerClass = null
    ): ResourceSchemaSet {

        // @todo is there better way to use the const
        $type = $this->detectResourceType($resourceClass);

        $schemaSet = new ResourceSchemaSet(
            $this->createIdentifier($type),
            $this->createReadSchema($resourceClass, $type),
            $this->createCreateSchema($type, $createResourceRequestHandlerClass),
            $this->createUpdateSchema($type, $updateResourceRequestHandlerClass),
            );

        // Add to collection by FQCN and resource type
        SchemaCollection::add($resourceClass, $schemaSet);
        SchemaCollection::add($type, $schemaSet);

        return $schemaSet;
    }

    /**
     * @throws ReflectionException
     */
    private function detectResourceType(string $resourceClass): string
    {
        $reflectionClass = new ReflectionClass($resourceClass);
        $constants = $reflectionClass->getConstants();
        $constantNames = array_keys($constants);

        if (true === in_array('TYPE', $constantNames)) {
            return $constants['TYPE'];
        }

        if (true === in_array('RESOURCE_TYPE', $constantNames)) {
            return $constants['RESOURCE_TYPE'];
        }

        throw new RuntimeException('Can\'t detect type for given resource: ' . $resourceClass);
    }
}
