<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema;

use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class IntegerSchema implements SchemaInterface
{
    /** @var null|int */
    private $example;

    /** @var null|string */
    private $description;

    public function __construct(?int $example, ?string $description)
    {
        $this->example = $example;
        $this->description = $description;
    }

    public function toOpenApi(): array
    {
        $schema = [
            'type' => 'integer',
        ];

        if (null !== $this->example) {
            $schema['example'] = $this->example;
        }

        if (null !== $this->description) {
            $schema['description'] = $this->description;
        }

        return $schema;
    }
}
