<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema;

use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class AttributeSchema implements SchemaInterface
{
    /** @var string */
    private $title;

    /** @var string */
    private $type;

    /** @var bool */
    private $required;

    /** @var bool */
    private $nullable;

    /** @var null|string */
    private $description;

    /** @var null|string */
    private $example;

    /** @var null|string */
    private $format;

    public function __construct(
        string $title,
        string $type,
        bool $required,
        bool $nullable,
        ?string $description,
        ?string $example,
        ?string $format
    ) {
        $this->title = $title;
        $this->type = $type;
        $this->required = $required;
        $this->nullable = $nullable;
        $this->description = $description;
        $this->example = $example;
        $this->format = $format;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function toOpenApi(): array
    {
        $schema = [
            'title' => $this->title,
            'type' => $this->type,
            'nullable' => $this->nullable,
        ];

        if (null !== $this->description) {
            $schema['description'] = $this->description;
        }

        if (null !== $this->example) {
            $schema['example'] = $this->example;
        }

        if (null !== $this->format) {
            $schema['format'] = $this->format;
        }

        return $schema;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}
