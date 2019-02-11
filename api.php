<?php

declare(strict_types=1);

use Example\Book\RequestHandler\Book\CreateBookRequestHandler;
use Example\Book\RequestHandler\Book\UpdateBookRequestHandler;
use Example\Book\Resource\Author;
use Example\Book\Resource\Book;
use Example\Book\Resource\Tag;
use JsonApiOpenApi\Model\JsonApi\Api\ResourceApiEndpointsFactory;
use JsonApiOpenApi\Model\JsonApi\Schema\Filter\IntegerFilterQueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\Filter\StringFilterQueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\Resource\ResourceSchemaFactory;
use JsonApiOpenApi\Model\JsonApi\Schema\SchemaCollection;
use JsonApiOpenApi\Model\OpenApi\Api;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/../vendor/autoload.php';

$bookResource = new Book(
    'd290f1ee-6c54-4b01-90e6-d701748f0851',
    'Kingdom of Hearts',
    'The best book of all times',
    20000,
    'd290f1ee-6c54-4b01-90e6-d701748f0851',
    [],
    );

$authorResource = new Author('d290f1ee-6c54-4b01-90e6-d701748f0851', 'Stephen King', []);
$tagResource = new Tag('d290f1ee-6c54-4b01-90e6-d701748f0851', 'Stephen King', []);

$resourceSchemaFactory = new ResourceSchemaFactory();

$createBookRequestHandler = new CreateBookRequestHandler();
$updateBookRequestHandler = new UpdateBookRequestHandler();

$resourceSchemaFactory->createSchemaSet($authorResource);
$resourceSchemaFactory->createSchemaSet($bookResource, $createBookRequestHandler, $updateBookRequestHandler);
$resourceSchemaFactory->createSchemaSet($tagResource);

$api = new Api(
    'Example API',
    '1.0.0',
    'Example API documentation for JSON:API'
);

ResourceApiEndpointsFactory::make()
    ->atPath('/books')
    ->forResource(Book::class)
    ->withGetSingle()
    ->withSingleIncludes(['author', 'tags'])
    ->withGetCollection()
    ->withCollectionFilters([
        new StringFilterQueryParam('author', false, 'Author ID filter'),
        new StringFilterQueryParam('title', false, 'Title filter'),
        new IntegerFilterQueryParam('priceMin', false, 'Price filter range'),
        new IntegerFilterQueryParam('priceMax', false, 'Price filter range'),
    ])
    ->withCollectionPagination(['offset', 'size'])
    ->withCollectionSorts(['title'])
    ->withCreate()
    ->withUpdate()
    ->addToApi($api);

$apiData = $api->toOpenApi();
$apiData['components']['schemas'] = SchemaCollection::toOpenApi();

echo Yaml::dump($apiData, 20, 2, Yaml::DUMP_OBJECT_AS_MAP) . PHP_EOL;