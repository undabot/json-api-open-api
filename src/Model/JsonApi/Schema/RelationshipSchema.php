<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema;

use JsonApiOpenApi\Helper\SchemaReferenceGenerator;
use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class RelationshipSchema implements SchemaInterface
{
    /** @var string */
    private $name;

    /** @var string|null */
    private $description;

    /** @var bool */
    private $required;

    /** @var bool */
    private $nullable;

    /** @var string */
    private $targetResourceClass;

    /** @var bool */
    private $toMany;

    public function __construct(
        string $name,
        ?string $description,
        bool $required,
        bool $nullable,
        string $targetResourceClass,
        bool $toMany
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->required = $required;
        $this->nullable = $nullable;
        $this->targetResourceClass = $targetResourceClass;
        $this->toMany = $toMany;
    }

    public function toOpenApi(): array
    {
        $ref = SchemaCollection::get($this->targetResourceClass)->getIdentifier()->getReference();
        $ref = SchemaReferenceGenerator::ref($ref);

        if (false === $this->toMany) {
            return [
                'type' => 'object',
                'required' => ['data'],
                'properties' => [
                    'data' => [
                        '$ref' => $ref,
                    ],
                ],
            ];
        }

        return [
            'type' => 'object',
            'required' => ['data'],
            'properties' => [
                'data' => [
                    'type' => 'array',
                    'items' => [
                        '$ref' => $ref,
                    ],
                ],
            ],
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}
