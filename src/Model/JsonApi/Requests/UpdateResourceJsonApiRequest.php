<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Requests;

use JsonApiOpenApi\Model\OpenApi\RequestInterface;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;

class UpdateResourceJsonApiRequest implements RequestInterface
{
    /** @var string */
    private $resourceType;

    /** @var ResourceSchemaInterface */
    private $schema;

    public function __construct(string $resourceType, ResourceSchemaInterface $schema)
    {
        $this->resourceType = $resourceType;
        $this->schema = $schema;
    }

    public function getContentType(): string
    {
        return 'application/vnd.api+json';
    }

    public function getSchemaReference(): string
    {
        return $this->schema->getReference();
    }
}
