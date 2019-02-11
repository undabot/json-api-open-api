<?php

declare(strict_types=1);

namespace Example\Book\Resource;

use Undabot\JsonApi\Model\Link\LinkInterface;
use Undabot\JsonApi\Model\Meta\MetaInterface;
use Undabot\JsonApi\Model\Resource\Attribute\AttributeCollectionInterface;
use Undabot\JsonApi\Model\Resource\Relationship\RelationshipCollectionInterface;
use Undabot\JsonApi\Model\Resource\ResourceInterface;
use Undabot\SymfonyJsonApi\Model\Attribute\ResourceAttributesFactory;
use Undabot\SymfonyJsonApi\Model\Relationship\ResourceRelationshipsFactory;
use JsonApiOpenApi\Annotation\Model as OA;

class Author implements ResourceInterface
{
    const TYPE = 'authors';

    /** @var string */
    private $id;

    /**
     * @var string
     * @OA\Attribute(description="Author name")
     */
    private $name;

    /**
     * @var null|string[]
     * @OA\ToMany(targetClass="\Example\Book\Resource\Book", description="Books")
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
