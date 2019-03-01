<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Api;

use JsonApiOpenApi\Model\JsonApi\Endpoint\CreateResourceEndpoint;
use JsonApiOpenApi\Model\JsonApi\Endpoint\GetResourceCollectionEndpoint;
use JsonApiOpenApi\Model\JsonApi\Endpoint\GetResourceEndpoint;
use JsonApiOpenApi\Model\JsonApi\Endpoint\UpdateResourceEndpoint;
use JsonApiOpenApi\Model\JsonApi\Schema\SchemaCollection;
use JsonApiOpenApi\Model\OpenApi\ApiInterface;
use JsonApiOpenApi\Model\OpenApi\SchemaInterface;

class ResourceApiEndpointsFactory
{
    /** @var string */
    private $resourceClassName;

    /** @var string */
    private $path;

    /** @var bool */
    private $getSingle;

    /** @var bool */
    private $getCollection;

    /** @var bool */
    private $create;

    /** @var bool */
    private $update;

    /** @var bool */
    private $delete;

    /** @var array */
    private $singleIncludes = [];

    /** @var array */
    private $singleFields = [];

    /** @var array */
    private $collectionIncludes = [];

    /** @var array */
    private $collectionFields = [];

    /** @var array */
    private $collectionFilters = [];

    /** @var array */
    private $collectionSorts = [];

    /** @var SchemaInterface|null */
    private $paginationSchema;

    public static function make()
    {
        return new self();
    }

    public function atPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function withSingleIncludes(array $singleIncludes): ResourceApiEndpointsFactory
    {
        $this->singleIncludes = $singleIncludes;

        return $this;
    }

    public function withSingleFields(array $singleFields): ResourceApiEndpointsFactory
    {
        $this->singleFields = $singleFields;

        return $this;
    }

    public function withCollectionIncludes(array $collectionIncludes): ResourceApiEndpointsFactory
    {
        $this->collectionIncludes = $collectionIncludes;

        return $this;
    }

    public function withCollectionFilters(array $collectionFilters): ResourceApiEndpointsFactory
    {
        $this->collectionFilters = $collectionFilters;

        return $this;
    }

    public function withCollectionSorts(array $collectionSorts): ResourceApiEndpointsFactory
    {
        $this->collectionSorts = $collectionSorts;

        return $this;
    }

    public function withCollectionPagination(SchemaInterface $paginationSchema): ResourceApiEndpointsFactory
    {
        $this->paginationSchema = $paginationSchema;

        return $this;
    }

    public function withGetSingle(): ResourceApiEndpointsFactory
    {
        $this->getSingle = true;

        return $this;
    }

    public function withGetCollection(): ResourceApiEndpointsFactory
    {
        $this->getCollection = true;

        return $this;
    }

    public function withCreate(): ResourceApiEndpointsFactory
    {
        $this->create = true;

        return $this;
    }

    public function withUpdate(): ResourceApiEndpointsFactory
    {
        $this->update = true;

        return $this;
    }

    public function withDelete(): ResourceApiEndpointsFactory
    {
        $this->delete = true;

        return $this;
    }

    public function addToApi(ApiInterface $api): void
    {
        if (true === $this->getCollection) {
            $getCollectionEndpoint = new GetResourceCollectionEndpoint(
                SchemaCollection::get($this->resourceClassName)->getReadModel(),
                $this->path,
                $this->collectionFilters,
                $this->collectionSorts,
                $this->collectionIncludes,
                $this->collectionFields,
                $this->paginationSchema
            // @todo error responses
            );

            $api->addEndpoint($getCollectionEndpoint);
        }

        if (true === $this->getSingle) {
            $getSingleResourceEndpoint = new GetResourceEndpoint(
                SchemaCollection::get($this->resourceClassName)->getReadModel(),
                $this->path,
                $this->singleIncludes,
                $this->singleFields
            // @todo error responses
            );

            $api->addEndpoint($getSingleResourceEndpoint);
        }

        if (true === $this->create) {
            $createResourceEndpoint = new CreateResourceEndpoint(
                SchemaCollection::get($this->resourceClassName)->getReadModel(),
                SchemaCollection::get($this->resourceClassName)->getCreateModel(),
                $this->path
            );

            $api->addEndpoint($createResourceEndpoint);
        }

        if (true === $this->update) {
            $createResourceEndpoint = new UpdateResourceEndpoint(
                SchemaCollection::get($this->resourceClassName)->getReadModel(),
                SchemaCollection::get($this->resourceClassName)->getUpdateModel(),
                $this->path
            );

            $api->addEndpoint($createResourceEndpoint);
        }
    }

    public function forResource(string $class): self
    {
        $this->resourceClassName = $class;

        return $this;
    }
}
