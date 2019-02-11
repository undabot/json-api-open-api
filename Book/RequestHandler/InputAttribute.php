<?php

declare(strict_types=1);

namespace Example\Book\RequestHandler;

class InputAttribute
{
    /** @var string */
    private $name;

    /** @var array */
    private $constraints;

    /** @var bool */
    private $optional;

    public function __construct(string $name, array $constraints, bool $optional = false)
    {
        $this->name = $name;
        $this->constraints = $constraints;

        // Is current attribute optional = can the client omit attribute? i.e. send payload without attribute key?
        $this->optional = $optional;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }
}
