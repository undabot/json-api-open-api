<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Annotation\Scanner;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\DocParser;
use JsonApiOpenApi\Annotation\Helper\PropertyMeta;
use JsonApiOpenApi\Annotation\Model\Attribute;
use JsonApiOpenApi\Helper\OpenApiTypeHelper;
use JsonApiOpenApi\Model\JsonApi\Schema\AttributeSchema;
use ReflectionClass;

class AttributeScanner
{
    public static function scan(string $resourceClass): array
    {
        $reflect = new ReflectionClass($resourceClass);
        AnnotationRegistry::registerLoader('class_exists');
        $docParser = new DocParser();
        $docParser->setIgnoreNotImportedAnnotations(true);
        $reader = new AnnotationReader($docParser);

        $attributes = [];

        $props = $reflect->getProperties();

        foreach ($props as $prop) {
            $propAnnotations = $reader->getPropertyAnnotations($prop);

            foreach ($propAnnotations as $propAnnotation) {

                if ($propAnnotation instanceof Attribute) {
                    $propMeta = new PropertyMeta($prop->getDocComment());
                    $propTitle = null !== $propAnnotation->title ? $propAnnotation->title : $prop->getName();
                    $propNullable = null !== $propAnnotation->nullable ? $propAnnotation->nullable : $propMeta->isNullable();
                    $propType = null !== $propAnnotation->type ? $propAnnotation->type : $propMeta->getType();
                    $propType = OpenApiTypeHelper::getOpenApiType($propType);

                    $attributes[$prop->getName()] = new AttributeSchema(
                        $propTitle,
                        $propType,
                        true,
                        $propNullable,
                        $propAnnotation->description,
                        $propAnnotation->example,
                        $propAnnotation->format
                    );
                }
            }
        }

        return $attributes;
    }
}
