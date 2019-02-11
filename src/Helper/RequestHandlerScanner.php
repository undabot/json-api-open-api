<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Helper;

use Example\Book\RequestHandler\InputAttribute;
use Example\Book\RequestHandler\InputRelationship;
use InvalidArgumentException;
use JsonApiOpenApi\Model\JsonApi\Schema\AttributeSchema;
use JsonApiOpenApi\Model\JsonApi\Schema\RelationshipSchema;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class RequestHandlerScanner
{
    /**
     * @throws ReflectionException
     */
    public static function getAttributes(string $requestHandlerClass): array
    {
        if (false === method_exists($requestHandlerClass, 'getAttributes')) {
            throw new InvalidArgumentException('RequestHandler doesn\'t have getAttributes method');
        }

        $reflectionClass = new ReflectionClass($requestHandlerClass);
        $requestHandlerInstance = $reflectionClass->newInstanceWithoutConstructor();

        $getAttributesMethod = new ReflectionMethod($requestHandlerInstance, 'getAttributes');
        $getAttributesMethod->setAccessible(true);

        $attributes = [];

        /** @var InputAttribute $definedAttribute */
        foreach ($getAttributesMethod->invoke($requestHandlerInstance) as $definedAttribute) {
            $type = null;
            $nullable = true;

            foreach ($definedAttribute->getConstraints() as $constraint) {
                if ($constraint instanceof NotNull || $constraint instanceof NotBlank) {
                    $nullable = false;
                }

                if ($constraint instanceof Type) {
                    $type = OpenApiTypeHelper::getOpenApiType($constraint->type);
                }
            }

            $attributes[$definedAttribute->getName()] = new AttributeSchema(
                $definedAttribute->getName(),
                $type,
                false === $definedAttribute->isOptional(),
                $nullable,
                null, // @todo add support for attribute descriptions on write models
                null,
                null,
                );
        }

        return $attributes;
    }

    /**
     * @throws ReflectionException
     */
    public static function getRelationships(string $requestHandlerClass): array
    {
        if (false === method_exists($requestHandlerClass, 'getRelationships')) {
            throw new InvalidArgumentException('RequestHandler doesn\'t have getRelationships method');
        }

        $reflectionClass = new ReflectionClass($requestHandlerClass);
        $requestHandlerInstance = $reflectionClass->newInstanceWithoutConstructor();
        $getRelationshipsMethod = new ReflectionMethod($requestHandlerInstance, 'getRelationships');
        $getRelationshipsMethod->setAccessible(true);

        $relationships = [];

        /** @var InputRelationship $inputRelationship */
        foreach ($getRelationshipsMethod->invoke($requestHandlerInstance) as $inputRelationship) {

            $relationships[$inputRelationship->getName()] = new RelationshipSchema(
                $inputRelationship->getName(),
                '', // @todo add support for relationships descriptions on write models
                false === $inputRelationship->isOptional(),
                $inputRelationship->isNullable(),
                $inputRelationship->getType(),
                $inputRelationship->isToMany()
            );
        }

        return $relationships;
    }
}
