<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Query;

use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class PageBasedPaginationQueryParam implements SchemaInterface
{
    public function toOpenApi(): array
    {
        return [
            'name' => 'page',
            'in' => 'query',
            'style' => 'deepObject',
            'explode' => true,
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'number' => [
                        'type' => 'integer',
                        'description' => 'Page number',
                        'example' => 1,
                    ],
                    'size' => [
                        'type' => 'integer',
                        'description' => 'Page size',
                        'example' => 20,
                    ],
                ],
            ],
        ];
    }
}
