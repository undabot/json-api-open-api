<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\JsonApi\Api;

use JsonApiOpenApi\Model\OpenApi\Api;
use JsonApiOpenApi\Model\OpenApi\EndpointInterface;

class ResourceApi extends Api
{
    /** @var string */
    private $title;

    /** @var string */
    private $version;

    /** @var string */
    private $description;

    /** @var string */
    private $path;

    /** @var array|EndpointInterface[] */
    private $endpoints = [];

    /** @var array */
    private $servers = [];

    // @todo add support for security schemas

    public function getEndpoints(): array
    {
        return $this->endpoints;
    }

    public function addEndpoint(EndpointInterface $endpoint)
    {
        $this->endpoints[] = $endpoint;
    }

    public function toOpenApi(): array
    {
        $openApiEndpoints = [];

        $api = [
            'openapi' => '3.0.0',
//servers:
//  - description: SwaggerHub API Auto Mocking
//    url: https://virtserver.swaggerhub.com/Undabot-Backend/BookAndZvook/1.0.0
            'info' => [
                'description' => 'This is a simple API',
                'version' => '"1.0.0"',
                'title' => 'Simple Inventory API',
                'contact' =>
                    ['email' => 'you@your-company.com'],
                'license' => [
                    'name' => 'Apache 2.0',
                    'url' => 'http://www.apache.org/licenses/LICENSE-2.0.html',
                ],
            ],
            'paths' => [
                $this->path => $openApiEndpoints,
            ],
        ];

        /** @var EndpointInterface $endpoint */
        foreach ($this->endpoints as $endpoint) {
            $path = $this->path . $endpoint->getPath();
            $api['paths'][$path][$endpoint->getMethod()] = $endpoint->toOpenApi();
        }

        return $api;
    }
}
