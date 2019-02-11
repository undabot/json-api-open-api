<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema;

use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class StringSchema implements SchemaInterface
{
    /** @var null|string */
    private $example;

    /** @var null|string */
    private $format;

    /** @var null|string */
    private $description;

    public function __construct(?string $example = null, ?string $description = null, ?string $format = null)
    {
        $this->example = $example;
        $this->description = $description;
        $this->format = $format;
    }

    public function toOpenApi(): array
    {
        $schema = [
            'type' => 'string',
        ];

        if (null !== $this->format) {
            $schema['format'] = $this->format;
        }

        if (null !== $this->example) {
            $schema['example'] = $this->example;
        }

        if (null !== $this->description) {
            $schema['description'] = $this->description;
        }

        return $schema;
    }
}
