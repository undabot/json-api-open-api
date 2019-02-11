<?php

declare(strict_types=1);

namespace Example\Book\RequestHandler;

abstract class AbstractResourceInputHandler
{
    abstract protected function getAttributes(): array;

    abstract protected function getRelationships(): array;
}
