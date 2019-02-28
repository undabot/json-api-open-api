<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Endpoint;

use JsonApiOpenApi\Helper\SchemaReferenceGenerator;
use JsonApiOpenApi\Model\JsonApi\Requests\UpdateResourceJsonApiRequest;
use JsonApiOpenApi\Model\JsonApi\Response\ResourceUpdatedResponse;
use JsonApiOpenApi\Model\OpenApi\EndpointInterface;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;
use JsonApiOpenApi\Model\OpenApi\ResponseInterface;

class UpdateResourceEndpoint implements EndpointInterface
{
    /** @var ResourceSchemaInterface */
    private $resourceReadSchema;

    /** @var ResourceSchemaInterface */
    private $resourceUpdateSchema;

    /** @var string */
    private $path;

    /** @var ResponseInterface[] */
    private $responses;

    public function __construct(
        ResourceSchemaInterface $resourceReadSchema,
        ResourceSchemaInterface $resourceUpdateSchema,
        string $path,
        array $errorResponses = []
    ) {
        $this->resourceReadSchema = $resourceReadSchema;
        $this->resourceUpdateSchema = $resourceUpdateSchema;
        $this->path = $path;

        $this->responses = array_merge([
            new ResourceUpdatedResponse($this->resourceReadSchema),
        ], $errorResponses);
    }

    public function getMethod(): string
    {
        return self::METHOD_PATCH;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    public function toOpenApi(): array
    {
        $responses = [];

        /** @var ResponseInterface $response */
        foreach ($this->responses as $response) {
            $responses[$response->getStatusCode()] = $response->toOpenApi();
        }

        $request = new UpdateResourceJsonApiRequest(
            $this->resourceUpdateSchema->getType(),
            $this->resourceUpdateSchema
        );

        return [
            'summary' => 'Update ' . $this->resourceReadSchema->getType(),
            'operationId' => 'update' . ucwords($this->resourceReadSchema->getType()),
            'description' => 'Update ' . $this->resourceReadSchema->getType() . ' resource',
            'responses' => $responses,
            'requestBody' => [
                'description' => $this->resourceReadSchema->getType() . ' update model',
                'required' => true,
                'content' => [
                    $request->getContentType() => [
                        'schema' => [
                            'type' => 'object',
                            'required' => ['data'],
                            'properties' => [
                                'data' => [
                                    '$ref' => SchemaReferenceGenerator::ref($request->getSchemaReference()),
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getParams(): ?array
    {
        return null;
    }
}
