<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface EndpointInterface
{
    public const METHOD_GET = 'get';
    public const METHOD_POST = 'post';
    public const METHOD_PUT = 'put';
    public const METHOD_PATCH = 'patch';

    public function getMethod(): string;

    public function getPath(): string;

    public function getResponses(): array;

    public function getParams(): ?array;

    public function toOpenApi(): array;
}
