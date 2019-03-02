<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Model\OpenApi;

class Server implements ServerInterface
{
    /** @var string */
    private $url;

    /** @var string|null */
    private $description;

    public function __construct(string $url, ?string $description = null)
    {
        $this->url = $url;
        $this->description = $description;
    }

    public function toOpenApi(): array
    {
        return [
            'url' => $this->url,
            'description' => $this->description,
        ];
    }
}
