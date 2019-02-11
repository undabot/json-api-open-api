<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Helper;

use Example\Book\RequestHandler\InputAttribute;
use Example\Book\RequestHandler\InputRelationship;
use JsonApiOpenApi\Model\JsonApi\Schema\AttributeSchema;
use JsonApiOpenApi\Model\JsonApi\Schema\RelationshipSchema;
use ReflectionMethod;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;
use Undabot\SymfonyJsonApi\RequestHandler\CreateResourceRequestHandlerInterface;
use Undabot\SymfonyJsonApi\RequestHandler\UpdateResourceRequestHandlerInterface;

class RequestHandlerScanner
{
    public static function getAttributes($requestHandler): array
    {
        if (false === is_subclass_of($requestHandler, CreateResourceRequestHandlerInterface::class) &&
            false === is_subclass_of($requestHandler, UpdateResourceRequestHandlerInterface::class)) {
            throw new \InvalidArgumentException('Invalid RequestHandler provided');
        }

        $attributes = [];

        if (false === method_exists($requestHandler, 'getAttributes')) {
            throw new \InvalidArgumentException('RequestHandler doesn\'t have getAttributes method');
        }

        $getAttributesMethod = new ReflectionMethod($requestHandler, 'getAttributes');
        $getAttributesMethod->setAccessible(true);

        /** @var InputAttribute $definedAttribute */
        foreach ($getAttributesMethod->invoke($requestHandler) as $definedAttribute) {

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
                null,
                null,
                null,
                );
        }

        return $attributes;
    }

    public static function getRelationships($requestHandler): array
    {
        if (false === is_subclass_of($requestHandler, CreateResourceRequestHandlerInterface::class) &&
            false === is_subclass_of($requestHandler, UpdateResourceRequestHandlerInterface::class)) {
            throw new \InvalidArgumentException('Invalid RequestHandler provided');
        }

        if (false === method_exists($requestHandler, 'getRelationships')) {
            throw new \InvalidArgumentException('RequestHandler doesn\'t have getAttributes method');
        }

        $getRelationshipsMethod = new ReflectionMethod($requestHandler, 'getRelationships');
        $getRelationshipsMethod->setAccessible(true);

        $relationships = [];

        /** @var InputRelationship $inputRelationship */
        foreach ($getRelationshipsMethod->invoke($requestHandler) as $inputRelationship) {

            $relationships[$inputRelationship->getName()] = new RelationshipSchema(
                $inputRelationship->getName(),
                '', // @todo
                false === $inputRelationship->isOptional(),
                $inputRelationship->isNullable(),
                $inputRelationship->getType(),
                $inputRelationship->isToMany()
            );
        }

        return $relationships;
    }
}
