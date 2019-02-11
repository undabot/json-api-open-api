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

class Book implements ResourceInterface
{
    const TYPE = 'books';

    /** @var string */
    private $id;

    /**
     * @var string
     * @OA\Attribute(description="Book title")
     */
    private $title;

    /**
     * @var string|null
     * @OA\Attribute(description="Book summary")
     */
    private $summary;

    /**
     * @var int
     * @OA\Attribute(description="Book price in Croatian lipa")
     */
    private $price;

    /**
     * @var string
     * @OA\ToOne(name="author", targetClass="\Example\Book\Resource\Author", description="Author")
     */
    private $authorId;

    /**
     * @var string[]|null
     * @OA\ToMany(targetClass="\Example\Book\Resource\Tag", description="Tags")
     */
    private $tags;

    public function __construct(string $id, string $title, ?string $summary, int $price, string $authorId, ?array $tags)
    {
        $this->id = $id;
        $this->title = $title;
        $this->summary = $summary;
        $this->price = $price;
        $this->authorId = $authorId;
        $this->tags = $tags;
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
            ->add('title', $this->title)
            ->add('summary', $this->summary)
            ->add('price', $this->price)
            ->get();
    }

    public function getRelationships(): ?RelationshipCollectionInterface
    {
        return ResourceRelationshipsFactory::make()
            ->toOne('author', Author::TYPE, $this->authorId)
            ->toMany('tags', Tag::TYPE, $this->tags)
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
