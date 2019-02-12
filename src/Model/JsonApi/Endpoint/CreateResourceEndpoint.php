<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Endpoint;

use JsonApiOpenApi\Helper\SchemaReferenceGenerator;
use JsonApiOpenApi\Model\JsonApi\Requests\CreateResourceJsonApiRequest;
use JsonApiOpenApi\Model\JsonApi\Response\ResourceCreatedResponse;
use JsonApiOpenApi\Model\OpenApi\EndpointInterface;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;
use JsonApiOpenApi\Model\OpenApi\ResponseInterface;

class CreateResourceEndpoint implements EndpointInterface
{
    /** @var ResourceSchemaInterface */
    private $resourceReadSchema;

    /** @var ResourceSchemaInterface */
    private $resourceCreateSchema;

    /** @var string */
    private $path;

    /** @var ResponseInterface[] */
    private $responses;

    public function __construct(
        ResourceSchemaInterface $resourceReadSchema,
        ResourceSchemaInterface $resourceCreateSchema,
        string $path,
        array $errorResponses = []
    ) {
        $this->resourceCreateSchema = $resourceCreateSchema;
        $this->resourceReadSchema = $resourceReadSchema;
        $this->path = $path;

        $this->responses = array_merge([
            new ResourceCreatedResponse($this->resourceReadSchema),
        ], $errorResponses);
    }

    public function getMethod(): string
    {
        return self::METHOD_POST;
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

        $request = new CreateResourceJsonApiRequest(
            $this->resourceCreateSchema->getType(),
            $this->resourceCreateSchema
        );

        return [
            'summary' => 'Create ' . $this->resourceReadSchema->getType(),
            'operationId' => 'create ' . ucwords($this->resourceReadSchema->getType()),
            'description' => 'Create ' . $this->resourceReadSchema->getType() . ' resource',
            'tags' => [$this->resourceReadSchema->getType()],
            'responses' => $responses,
            'requestBody' => [
                'description' => $this->resourceReadSchema->getType() . ' create model',
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
