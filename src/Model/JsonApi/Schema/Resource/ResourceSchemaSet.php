<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Schema\Resource;

use JsonApiOpenApi\Model\OpenApi\ResourceSchemaInterface;

class ResourceSchemaSet
{
    /** @var ResourceIdentifierSchema|null */
    private $identifier;

    /** @var ResourceReadSchema|null */
    private $readModel;

    /** @var ResourceCreateSchema|null */
    private $createModel;

    /** @var ResourceUpdateSchema|null */
    private $updateModel;

    public function __construct(
        ?ResourceIdentifierSchema $identifier,
        ?ResourceSchemaInterface $readModel,
        ?ResourceSchemaInterface $createModel,
        ?ResourceSchemaInterface $updateModel
    ) {
        $this->identifier = $identifier;
        $this->readModel = $readModel;
        $this->createModel = $createModel;
        $this->updateModel = $updateModel;
    }

    public function getIdentifier(): ?ResourceIdentifierSchema
    {
        return $this->identifier;
    }

    public function getReadModel(): ?ResourceReadSchema
    {
        return $this->readModel;
    }

    public function getCreateModel(): ?ResourceCreateSchema
    {
        return $this->createModel;
    }

    public function getUpdateModel(): ?ResourceUpdateSchema
    {
        return $this->updateModel;
    }
}
