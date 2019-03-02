<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Filter;

use JsonApiOpenApi\Model\JsonApi\Schema\IntegerSchema;
use JsonApiOpenApi\Model\JsonApi\Schema\StringSchema;
use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

final class FilterQueryParam
{
    /** @var string */
    private $name;

    /** @var SchemaInterface */
    private $schema;

    /** @var bool */
    private $required;

    public static function makeInt(
        string $name,
        bool $required = false,
        ?string $description = null,
        ?string $example = null
    ) {
        return new self($name, new IntegerSchema($example, $description), $required);
    }

    public static function makeString(
        string $name,
        bool $required = false,
        ?string $description = null,
        ?string $example = null
    ) {
        return new self($name, new StringSchema($example, $description), $required);
    }

    public function __construct(string $name, SchemaInterface $schema, bool $required = false)
    {
        $this->name = $name;
        $this->schema = $schema;
        $this->required = $required;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSchema(): SchemaInterface
    {
        return $this->schema;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}
