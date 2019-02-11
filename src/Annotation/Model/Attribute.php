<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Annotation\Model;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Attribute extends Annotation
{
    /** @var string */
    public $title;

    /** @var string */
    public $type;

    /** @var bool */
    public $nullable;

    /** @var null|string */
    public $description;

    /** @var null|string */
    public $example;

    /** @var null|string */
    public $format;
}
