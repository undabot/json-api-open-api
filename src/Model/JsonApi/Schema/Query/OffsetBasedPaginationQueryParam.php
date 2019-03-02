<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Query;

use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class OffsetBasedPaginationQueryParam implements SchemaInterface
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
                    'offset' => [
                        'type' => 'integer',
                        'description' => 'Pagination offset (start from)',
                        'example' => 0,
                    ],
                    'limit' => [
                        'type' => 'integer',
                        'description' => 'Page size',
                        'example' => 20,
                    ],
                ],
            ],
        ];
    }
}
