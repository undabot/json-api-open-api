<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Annotation\Scanner;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use JsonApiOpenApi\Annotation\Helper\PropertyMeta;
use JsonApiOpenApi\Annotation\Model\ToMany;
use JsonApiOpenApi\Annotation\Model\ToOne;
use JsonApiOpenApi\Model\JsonApi\Schema\RelationshipSchema;
use ReflectionClass;

class RelationshipScanner
{
    public static function scan(string $resourceClass): array
    {
        $reflect = new ReflectionClass($resourceClass);
        AnnotationRegistry::registerLoader('class_exists');
        $docParser = new DocParser();
        $docParser->setIgnoreNotImportedAnnotations(true);
        $reader = new AnnotationReader($docParser);

        $relationships = [];

        $props = $reflect->getProperties();

        foreach ($props as $prop) {
            $propAnnotations = $reader->getPropertyAnnotations($prop);

            foreach ($propAnnotations as $propAnnotation) {

                if ($propAnnotation instanceof ToOne || $propAnnotation instanceof ToMany) {
                    $propMeta = new PropertyMeta($prop->getDocComment());
                    $propName = null !== $propAnnotation->name ? $propAnnotation->name : $prop->getName();
                    $propNullable = null !== $propAnnotation->nullable ? $propAnnotation->nullable : $propMeta->isNullable();

                    $relationships[$prop->getName()] = new RelationshipSchema(
                        $propName,
                        $propAnnotation->description,
                        true,
                        $propNullable,
                        $propAnnotation->targetClass,
                        $propAnnotation instanceof ToMany
                    );
                }
            }
        }

        return $relationships;
    }
}
