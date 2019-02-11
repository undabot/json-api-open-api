<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Filter;

use JsonApiOpenApi\Model\JsonApi\Schema\QueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\StringSchema;
use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class StringFilterQueryParam implements SchemaInterface
{
    /** @var string */
    private $name;

    /** @var bool */
    private $required;

    /** @var string */
    private $description;

    /** @var string|null */
    private $example;

    /** @var string|null */
    private $format;

    public function __construct(
        string $name,
        bool $required,
        string $description,
        ?string $example = null,
        ?string $format = null
    ) {
        $this->name = $name;
        $this->required = $required;
        $this->description = $description;
        $this->example = $example;
        $this->format = $format;
    }

    public function toOpenApi(): array
    {
        $schema = new StringSchema($this->example, $this->description, $this->format);
        $filterQueryParamName = "filter[{$this->name}]";
        $param = new QueryParam($filterQueryParamName, $this->required, $this->description, $schema);

        return $param->toOpenApi();
    }
}
