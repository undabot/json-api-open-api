<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface ResponseInterface
{
    public function getStatusCode(): int;

    public function getContentType(): string;

    public function getDescription(): ?string;

    public function toOpenApi();
}
