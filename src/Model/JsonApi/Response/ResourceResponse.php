<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Response;

use JsonApiOpenApi\Helper\SchemaReferenceGenerator;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;
use JsonApiOpenApi\Model\OpenApi\ResponseInterface;

class ResourceResponse implements ResponseInterface
{
    use IncludableResponseTrait;

    /** @var ResourceSchemaInterface */
    private $resourceSchema;

    /** @var array */
    private $includes;

    public function __construct(ResourceSchemaInterface $resourceSchema, array $includes = [])
    {
        $this->resourceSchema = $resourceSchema;
        $this->includes = $includes;
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
        return 'Successful response for getting the resource instance';
    }

    public function toOpenApi()
    {
        $responseContentSchema = [
            'schema' => [
                'type' => 'object',
                'required' => ['data'],
                'properties' => [
                    'data' => [
                        '$ref' => SchemaReferenceGenerator::ref($this->resourceSchema->getReference()),
                    ],
                ],
            ],
        ];

        if (false === empty($this->includes)) {
            $responseContentSchema = $this->generateIncludedResponseProperty($responseContentSchema, $this->includes);
        }

        $response = [
            'description' => $this->getDescription(),
            'content' => [
                $this->getContentType() => $responseContentSchema,
            ],
        ];

        return $response;
    }
}
