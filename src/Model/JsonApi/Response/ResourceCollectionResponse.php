<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Response;

use JsonApiOpenApi\Helper\SchemaReferenceGenerator;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;
use JsonApiOpenApi\Model\OpenApi\ResponseInterface;

class ResourceCollectionResponse implements ResponseInterface
{
    /** @var ResourceSchemaInterface */
    private $resourceSchema;

    public function __construct(ResourceSchemaInterface $resourceSchema)
    {
        $this->resourceSchema = $resourceSchema;
    }

    public function getStatusCode(): int
    {
        return 200;
    }

    public function getContentType(): string
    {
        return 'application/vnd.api+json';
    }

    public function getDescription(): ?string
    {
        return 'Successful response for getting the collection of resources';
    }

    public function toOpenApi()
    {
        return [
            'description' => $this->getDescription(),
            'content' => [
                $this->getContentType() => [
                    'schema' => [
                        'type' => 'object',
                        'required' => ['data'],
                        'properties' => [
                            'data' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => SchemaReferenceGenerator::ref($this->resourceSchema->getReference()),
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
