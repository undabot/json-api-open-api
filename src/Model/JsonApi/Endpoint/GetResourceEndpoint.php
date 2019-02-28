<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Endpoint;

use JsonApiOpenApi\Model\JsonApi\Requests\GetResourceJsonApiRequest;
use JsonApiOpenApi\Model\JsonApi\Response\ResourceResponse;
use JsonApiOpenApi\Model\JsonApi\Schema\PathParam;
use JsonApiOpenApi\Model\JsonApi\Schema\Query\IncludeQueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\UuidSchema;
use JsonApiOpenApi\Model\OpenApi\EndpointInterface;
use JsonApiOpenApi\Model\OpenApi\RequestInterface;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;
use JsonApiOpenApi\Model\OpenApi\ResponseInterface;

class GetResourceEndpoint implements EndpointInterface
{
    /** @var ResourceSchemaInterface */
    private $resourceSchema;

    /** @var string */
    private $path;

    /** @var ResponseInterface[] */
    private $responses;

    /** @var null|array */
    private $includes;

    /** @var null|array */
    private $fields;

    public function __construct(
        ResourceSchemaInterface $resourceSchema,
        string $path,
        ?array $includes,
        ?array $fields,
        array $errorResponses = []
    ) {
        $this->resourceSchema = $resourceSchema;
        $this->path = $path;

        $this->responses = array_merge([
            new ResourceResponse($this->resourceSchema),
        ], $errorResponses);

        $this->includes = $includes;
        $this->fields = $fields;
    }

    public function getMethod(): string
    {
        return self::METHOD_GET;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    public function getParams(): ?array
    {
        $params = [];

        $idPathParam = new PathParam('id', true, 'Requested resource ID', new UuidSchema());
        $params[] = $idPathParam->toOpenApi();

        if (null !== $this->includes) {
            $include = new IncludeQueryParam($this->includes);
            $params[] = $include->toOpenApi();
        }

        if (null !== $this->fields) {
            // @todo Add support for fields
        }

        return $params;
    }

    public function toOpenApi(): array
    {
        $responses = [];

        /** @var ResponseInterface $response */
        foreach ($this->responses as $response) {
            $responses[$response->getStatusCode()] = $response->toOpenApi();
        }

        return [
            'summary' => 'Get ' . $this->resourceSchema->getType(),
            'operationId' => 'get' . ucwords($this->resourceSchema->getType()),
            'description' => 'Get single ' . $this->resourceSchema->getType() . ' resource',
            'tags' => [$this->resourceSchema->getType()],
            'parameters' => $this->getParams(),
            'responses' => $responses,
        ];
    }

    public function getPath(): string
    {
        return $this->path . '/{id}';
    }
}
