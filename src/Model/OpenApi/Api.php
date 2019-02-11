<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

class Api implements ApiInterface
{
    /** @var string */
    private $title;

    /** @var string */
    private $version;

    /** @var string */
    private $description;

    /** @var EndpointInterface[] */
    private $endpoints = [];

    /** @var array */
    private $servers = [];

    // @todo add support for security schemas

    public function __construct(string $title, string $version, string $description)
    {
        $this->title = $title;
        $this->version = $version;
        $this->description = $description;
    }

    public function addEndpoint(EndpointInterface $endpoint)
    {
        $this->endpoints[] = $endpoint;
    }

    public function addServer(ServerInterface $server)
    {
        $this->servers[] = $server;
    }

    public function toOpenApi(): array
    {
        $api = [
            'openapi' => '3.0.0',
            // @todo servers
            // @todo contact
            // @todo license
            'info' => [
                'description' => $this->description,
                'version' => $this->version,
                'title' => $this->title,
            ],
            'paths' => [],
        ];

        /** @var EndpointInterface $endpoint */
        foreach ($this->endpoints as $endpoint) {
            $api['paths'][$endpoint->getPath()][$endpoint->getMethod()] = $endpoint->toOpenApi();
        }

        return $api;
    }
}
