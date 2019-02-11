<?php

declare(strict_types=1);

namespace Example\Book\RequestHandler\Book;

use Example\Book\RequestHandler\AbstractResourceInputHandler;
use Example\Book\RequestHandler\InputAttribute;
use Example\Book\RequestHandler\InputRelationship;
use Example\Book\Resource\Author;
use Example\Book\Resource\Tag;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Undabot\JsonApi\Model\Request\UpdateResourceRequestInterface;
use Undabot\SymfonyJsonApi\RequestHandler\UpdateResourceRequestHandlerInterface;
use Undabot\SymfonyJsonApi\Response\JsonApiResponseInterface;

class UpdateBookRequestHandler extends AbstractResourceInputHandler implements UpdateResourceRequestHandlerInterface
{
    protected function getAttributes(): array
    {
        return [
            new InputAttribute('title', [
                new NotBlank(),
                new Type(['type' => 'string']),
                new Length(['max' => 255]),
            ], true),
            new InputAttribute('summary', [
                new NotBlank(),
                new Type(['type' => 'string']),
                new Length(['max' => 255]),
            ], true),
            new InputAttribute('price', [
                new NotBlank(),
                new Type(['type' => 'string']),
                new GreaterThan(['value' => 0]),
            ], true),
        ];
    }

    protected function getRelationships(): array
    {
        return [
            InputRelationship::toOne('author', Author::TYPE, true),
            InputRelationship::toMany('tags', Tag::TYPE, true),
        ];
    }

    public function handle(UpdateResourceRequestInterface $request): JsonApiResponseInterface
    {
    }
}
