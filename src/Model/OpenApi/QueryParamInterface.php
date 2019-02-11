<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface QueryParamInterface
{
    public function getName(): string;

    public function isRequired(): bool;

    public function getSchema(): SchemaInterface;

    public function getDescription(): ?string;
}
