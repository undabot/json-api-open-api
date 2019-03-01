<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Filter;

use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class FilterSetQueryParam implements SchemaInterface
{
    /** @var string */
    private $name;

    /** @var FilterQueryParam[] */
    private $filters;

    public function __construct(string $name, array $filters)
    {
        $this->name = $name;
        $this->filters = $filters;
    }

    public function toOpenApi(): array
    {
        $schema = [
            'name' => $this->name,
            'in' => 'query',
            'style' => 'deepObject',
            'explode' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [],
            ],
        ];

        /** @var FilterQueryParam $filter */
        foreach ($this->filters as $filter) {
            $schema['schema']['properties'][$filter->getName()] = $filter->getSchema()->toOpenApi();
        }

        return $schema;
    }
}
