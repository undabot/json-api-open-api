<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Annotation\Helper;

class PropertyMeta
{
    /** @var string */
    private $docBlock;

    public function __construct(string $docBlock)
    {
        $this->docBlock = $docBlock;
    }

    private function getTypes(): array
    {
        preg_match('/@var (.*)/m', $this->docBlock, $result);
        $typeString = $result[1] ?? null;
        if (null === $typeString) {
            return [];
        }

        return explode('|', $typeString);
    }

    public function isNullable(): bool
    {
        return true === in_array('null', $this->getTypes());
    }

    public function getType(): ?string
    {
        return $this->getTypes()[0] ?? null;
    }
}
