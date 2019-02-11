<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Filter;

use JsonApiOpenApi\Model\JsonApi\Schema\IntegerSchema;
use JsonApiOpenApi\Model\JsonApi\Schema\QueryParam;
use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class IntegerFilterQueryParam implements SchemaInterface
{
    /** @var string */
    private $name;

    /** @var bool */
    private $required;

    /** @var string */
    private $description;

    /** @var string|null */
    private $example;

    public function __construct(
        string $name,
        bool $required,
        string $description,
        ?string $example = null
    ) {
        $this->name = $name;
        $this->required = $required;
        $this->description = $description;
        $this->example = $example;
    }

    public function toOpenApi(): array
    {
        $schema = new IntegerSchema($this->example, $this->description);
        $filterQueryParamName = "filter[{$this->name}]";
        $param = new QueryParam($filterQueryParamName, $this->required, $this->description, $schema);

        return $param->toOpenApi();
    }
}
