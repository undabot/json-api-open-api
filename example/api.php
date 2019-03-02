<?php

declare(strict_types=1);

use Example\Book\RequestHandler\Book\CreateBookRequestHandler;
use Example\Book\RequestHandler\Book\UpdateBookRequestHandler;
use Example\Book\Resource\Author;
use Example\Book\Resource\Book;
use Example\Book\Resource\Tag;
use JsonApiOpenApi\Model\JsonApi\Api\ResourceApiEndpointsFactory;
use JsonApiOpenApi\Model\JsonApi\Schema\Filter\FilterQueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\Query\OffsetBasedPaginationQueryParam;
use JsonApiOpenApi\Model\JsonApi\Schema\Resource\ResourceSchemaFactory;
use JsonApiOpenApi\Model\JsonApi\Schema\SchemaCollection;
use JsonApiOpenApi\Model\OpenApi\Api;
use JsonApiOpenApi\Model\OpenApi\Server;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/../vendor/autoload.php';

$resourceSchemaFactory = new ResourceSchemaFactory();

$resourceSchemaFactory->createSchemaSet(Author::class);
$resourceSchemaFactory->createSchemaSet(Book::class, CreateBookRequestHandler::class, UpdateBookRequestHandler::class);
$resourceSchemaFactory->createSchemaSet(Tag::class);

$api = new Api(
    'Example API',
    '1.0.0',
    'Example API documentation for JSON:API'
);

$api->addServer(new Server('http://jsonapi.undabot.com'));

ResourceApiEndpointsFactory::make()
    ->atPath('/books')
    ->forResource(Book::class)
    ->withGetSingle()
    ->withSingleIncludes(['author', 'tags'])
    ->withCollectionIncludes(['author', 'tags'])
    ->withGetCollection()
    ->withCollectionFilters([
        FilterQueryParam::makeString('author', false, 'Author ID filter'),
        FilterQueryParam::makeString('author', false, 'Author ID filter'),
        FilterQueryParam::makeString('title', false, 'Title filter'),
        FilterQueryParam::makeInt('priceMin', false, 'Price filter range'),
        FilterQueryParam::makeInt('priceMax', false, 'Price filter range'),
    ])
    ->withCollectionPagination(new OffsetBasedPaginationQueryParam())
    ->withCollectionSorts(['title'])
    ->withCreate()
    ->withUpdate()
    ->addToApi($api);

$apiData = $api->toOpenApi();
$apiData['components']['schemas'] = SchemaCollection::toOpenApi();

echo Yaml::dump($apiData, 20, 2, Yaml::DUMP_OBJECT_AS_MAP) . PHP_EOL;