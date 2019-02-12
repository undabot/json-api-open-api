<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Annotation\Model;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class ToOne extends Relationship
{
    /** @var string */
    public $name;

    /** @var string */
    public $targetClass;

    /** @var bool */
    public $nullable = false;

    /** @var null|string */
    public $description;

    public function isNullable(): bool
    {
        return $this->nullable;
    }

}
