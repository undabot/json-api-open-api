<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Query;

use JsonApiOpenApi\Model\JsonApi\Schema\QueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\StringSchema;

class IncludeQueryParam extends QueryParam
{
    public function __construct(array $includes, ?string $description = null)
    {
        $includesString = implode(', ', $includes);
        $schema = new StringSchema($includesString);

        if (null === $description) {
            $description = 'Relationships to be included. Available: ' . $includesString;
        }

        parent::__construct('include', false, $description, $schema);
    }
}
