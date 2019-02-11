<?php

declare(strict_types=1);

namespace Example\Book\Resource;

use JsonApiOpenApi\Annotation\Model as OA;
use Undabot\JsonApi\Model\Link\LinkInterface;
use Undabot\JsonApi\Model\Meta\MetaInterface;
use Undabot\JsonApi\Model\Resource\Attribute\AttributeCollectionInterface;
use Undabot\JsonApi\Model\Resource\Relationship\RelationshipCollectionInterface;
use Undabot\JsonApi\Model\Resource\ResourceInterface;
use Undabot\SymfonyJsonApi\Model\Attribute\ResourceAttributesFactory;
use Undabot\SymfonyJsonApi\Model\Relationship\ResourceRelationshipsFactory;

class Tag implements ResourceInterface
{
    const TYPE = 'tags';

    /** @var string */
    private $id;

    /**
     * @var string
     * @OA\Attribute(description="Tag name")
     */
    private $name;

    /**
     * @OA\ToMany(name="books", targetClass="\Example\Book\Resource\Book", description="Books")
     * @var string[]
     */
    private $bookIds;

    public function __construct(string $id, string $name, ?array $bookIds)
    {
        $this->id = $id;
        $this->name = $name;
        $this->bookIds = $bookIds;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getAttributes(): ?AttributeCollectionInterface
    {
        return ResourceAttributesFactory::make()
            ->add('name', $this->name)
            ->get();
    }

    public function getRelationships(): ?RelationshipCollectionInterface
    {
        return ResourceRelationshipsFactory::make()
            ->toMany('books', Book::TYPE, $this->bookIds)
            ->get();
    }

    public function getSelfUrl(): ?LinkInterface
    {
        return null;
    }

    public function getMeta(): ?MetaInterface
    {
        return null;
    }
}
