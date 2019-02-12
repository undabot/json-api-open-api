<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Endpoint;

use JsonApiOpenApi\Model\JsonApi\Response\ResourceCollectionResponse;
use JsonApiOpenApi\Model\JsonApi\Schema\Query\IncludeQueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\Query\PaginationQueryParam;
use JsonApiOpenApi\Model\OpenApi\EndpointInterface;
use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;
use JsonApiOpenApi\Model\OpenApi\ResponseInterface;

class GetResourceCollectionEndpoint implements EndpointInterface
{
    /** @var ResourceSchemaInterface */
    private $resourceSchema;

    /** @var string */
    private $path;

    /** @var ResponseInterface[] */
    private $responses;

    /** @var array */
    private $filters = [];

    /** @var array */
    private $includes = [];

    /** @var array */
    private $fields = [];

    /** @var array */
    private $sorts = [];

    /** @var null|array */
    private $pagination;

    public function __construct(
        ResourceSchemaInterface $resourceSchema,
        string $path,
        array $filters = [],
        array $sorts = [],
        array $includes = [],
        array $fields = [],
        array $pagination = null,
        array $errorResponses = []
    ) {
        $this->resourceSchema = $resourceSchema;
        $this->path = $path;

        $this->responses = array_merge([
            new ResourceCollectionResponse($this->resourceSchema),
        ], $errorResponses);

        $this->filters = $filters;
        $this->sorts = $sorts;
        $this->includes = $includes;
        $this->fields = $fields;
        $this->pagination = $pagination;
    }

    public function getMethod(): string
    {
        return self::METHOD_GET;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    public function getParams(): array
    {
        $queryParams = [];

        if (0 !== count($this->includes)) {
            $include = new IncludeQueryParam($this->includes);
            $queryParams[] = $include->toOpenApi();
        }

        if (null !== $this->fields) {
            // @todo Add support for fields
        }

        if (false === empty($this->filters)) {
            foreach ($this->filters as $filter) {
                $queryParams[] = $filter->toOpenApi();
            }
        }

        if (false === empty($this->pagination)) {
            foreach ($this->pagination as $paginationParameter) {
                $paginationQueryParam = new PaginationQueryParam($paginationParameter);
                $queryParams[] = $paginationQueryParam->toOpenApi();
            }
        }

        return $queryParams;
    }

    public function toOpenApi(): array
    {
        $responses = [];

        /** @var ResponseInterface $response */
        foreach ($this->responses as $response) {
            $responses[$response->getStatusCode()] = $response->toOpenApi();
        }

        return [
            'summary' => 'List ' . $this->resourceSchema->getType(),
            'operationId' => 'list' . ucwords($this->resourceSchema->getType()) . 'Collection',
            'description' => 'List collection of ' . $this->resourceSchema->getType(),
            'parameters' => $this->getParams(),
            'tags' => [$this->resourceSchema->getType()],
            'responses' => $responses,
        ];
    }

}
