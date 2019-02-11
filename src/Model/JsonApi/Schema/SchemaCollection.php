<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema;

use JsonApiOpenApi\Model\JsonApi\Schema\Resource\ResourceSchemaSet;

class SchemaCollection
{
    private static $schemas = [];

    private static function normalizeClassName(string $className)
    {
        $targetClassName = $className;
        if ($targetClassName[0] === "\\") {
            $targetClassName = substr($targetClassName, 1);
        }

        return $targetClassName;
    }

    public static function add(string $resourceClass, ResourceSchemaSet $resourceSchemaSet)
    {
        $resourceClass = static::normalizeClassName($resourceClass);
        if (true === self::exists($resourceClass)) {
            throw new \Exception('Already exists');
        }

        static::$schemas[$resourceClass] = $resourceSchemaSet;
    }

    public static function exists(string $resourceClass): bool
    {
        $resourceClass = static::normalizeClassName($resourceClass);

        return isset(static::$schemas[$resourceClass]);
    }

    public static function get(string $className): ResourceSchemaSet
    {
        $className = static::normalizeClassName($className);

        return static::$schemas[$className];
    }

    public static function toOpenApi()
    {
        $data = [];

        /** @var ResourceSchemaSet $schemaSet */
        foreach (static::$schemas as $schemaSet) {
            if (null !== $schemaSet->getIdentifier()) {
                $data[$schemaSet->getIdentifier()->getReference()] = $schemaSet->getIdentifier()->toOpenApi();
            }

            if (null !== $schemaSet->getReadModel()) {
                $data[$schemaSet->getReadModel()->getReference()] = $schemaSet->getReadModel()->toOpenApi();
            }

            if (null !== $schemaSet->getCreateModel()) {
                $data[$schemaSet->getCreateModel()->getReference()] = $schemaSet->getCreateModel()->toOpenApi();
            }

            if (null !== $schemaSet->getUpdateModel()) {
                $data[$schemaSet->getUpdateModel()->getReference()] = $schemaSet->getUpdateModel()->toOpenApi();
            }
        }

        return $data;
    }
}
