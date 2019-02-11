<?php

declare(strict_types=1);

namespace Example\Book\RequestHandler;


final class InputRelationship
{
    const TYPE_TO_ONE = 'toOne';
    const TYPE_TO_MANY = 'toMany';

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var string */
    private $relationshipType;

    /** @var bool */
    private $nullable;

    /** @var array */
    private $constraints;

    /** @var bool */
    private $optional;

    public static function toOne(
        string $name,
        string $type,
        bool $nullable = true,
        array $constraints = [],
        bool $optional = false
    ): self {
        return new self($name, $type, self::TYPE_TO_ONE, $nullable, $constraints, $optional);
    }

    public static function toMany(
        string $name,
        string $type,
        bool $nullable = true,
        array $constraints = [],
        bool $optional = false
    ): self {
        return new self($name, $type, self::TYPE_TO_MANY, $nullable, $constraints, $optional);
    }

    private function __construct(
        string $name,
        string $type,
        string $relationshipType,
        bool $nullable,
        array $constraints,
        bool $optional
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->relationshipType = $relationshipType;
        $this->nullable = $nullable;
        $this->constraints = $constraints;
        $this->optional = $optional;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function isToMany()
    {
        return self::TYPE_TO_MANY === $this->relationshipType;
    }

    public function isToOne()
    {
        return self::TYPE_TO_ONE === $this->relationshipType;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }
}
