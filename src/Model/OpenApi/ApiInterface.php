<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

interface ApiInterface
{
    public function addEndpoint(EndpointInterface $endpoint);

    public function addServer(ServerInterface $server);

    public function toOpenApi(): array;
}
