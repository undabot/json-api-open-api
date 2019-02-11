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
use Undabot\JsonApi\Model\Request\CreateResourceRequestInterface;
use Undabot\SymfonyJsonApi\RequestHandler\CreateResourceRequestHandlerInterface;
use Undabot\SymfonyJsonApi\Response\JsonApiResponseInterface;

class CreateBookRequestHandler extends AbstractResourceInputHandler implements CreateResourceRequestHandlerInterface
{
    protected function getAttributes(): array
    {
        return [
            new InputAttribute('title', [
                new NotBlank(),
                new Type(['type' => 'string']),
                new Length(['max' => 255]),
            ]),
            new InputAttribute('summary', [
                new NotBlank(),
                new Type(['type' => 'string']),
                new Length(['max' => 255]),
            ]),
            new InputAttribute('price', [
                new NotBlank(),
                new Type(['type' => 'int']),
                new GreaterThan(['value' => 0]),
            ]),
        ];
    }

    protected function getRelationships(): array
    {
        return [
            InputRelationship::toOne('author', Author::TYPE, false),
            InputRelationship::toMany('tags', Tag::TYPE, false),
        ];
    }

    public function handle(CreateResourceRequestInterface $request): JsonApiResponseInterface
    {
    }
}
