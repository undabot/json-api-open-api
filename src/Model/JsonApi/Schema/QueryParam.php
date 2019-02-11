<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema;

use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class QueryParam implements SchemaInterface
{
    /** @var string */
    private $name;

    /** @var bool */
    private $required;

    /** @var string */
    private $description;

    /** @var SchemaInterface */
    private $schema;

    public function __construct(string $name, bool $required, string $description, SchemaInterface $schema)
    {
        $this->name = $name;
        $this->required = $required;
        $this->description = $description;
        $this->schema = $schema;
    }

    public function toOpenApi(): array
    {
        return [
            'in' => 'query',
            'name' => $this->name,
            'required' => $this->required,
            'description' => $this->description,
            'schema' => $this->schema->toOpenApi(),
        ];
    }
}
