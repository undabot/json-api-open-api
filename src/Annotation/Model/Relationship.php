<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Annotation\Model;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
abstract class Relationship extends Annotation
{
}
