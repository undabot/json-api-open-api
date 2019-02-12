<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Query;

use JsonApiOpenApi\Model\JsonApi\Schema\QueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\StringSchema;

class PaginationQueryParam extends QueryParam
{
    public function __construct(string $parameterName, bool $required = true)
    {
        $schema = new StringSchema(null, null, null);
        parent::__construct("page[{$parameterName}]", $required, 'Pagination parameter', $schema);
    }
}
