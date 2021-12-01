<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL\Test;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use Laminas\Http\Request;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Riddlestone\Brokkr\DoctrineGraphQL\Test\Classes\Entities\TestEntity;
use Riddlestone\Brokkr\DoctrineGraphQL\Test\Classes\Entities\TestParentEntity;

class ApplicationTest extends AbstractHttpControllerTestCase
{
    protected function setUp(): void
    {
        $this->setApplicationConfig(
            require __DIR__ . '/test-application/config/application.php'
        );
        parent::setUp();

        /** @var EntityManager $entityManager */
        $entityManager = $this->getApplicationServiceLocator()->get(EntityManager::class);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadata);
    }

    /**
     * @coversNothing
     * @dataProvider requestsAndResponses
     * @throws Exception
     */
    public function testResponses(string $requestString, int $responseCode, string $responseString): void
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getApplicationServiceLocator()->get(EntityManager::class);
        $parents = [];
        foreach(['Foo', 'Bar'] as $name) {
            $entity = new TestParentEntity();
            $entity->setName($name);
            $entityManager->persist($entity);
            $parents[$name] = $entity;
        }
        foreach(['foo' => 'Foo', 'bar' => 'Foo', 'baz' => null] as $name => $parentName) {
            $entity = new TestEntity();
            $entity->setName($name);
            if ($parentName) {
                $entity->setParent($parents[$parentName]);
            }
            $entityManager->persist($entity);
        }
        $entityManager->flush();

        /** @var Request $request */
        $request = $this->getRequest();
        $request->getHeaders()->addHeaderLine('Content-Type', 'application/json');
        $this->dispatch('/graphql', 'POST', ['query' => 'query ' . $requestString]);
        $this->assertResponseStatusCode($responseCode);
        $this->assertEquals('{"data":' . $responseString . '}', $this->getResponse()->getContent());
    }

    public function requestsAndResponses(): array
    {
        return [
            ['{ test_entities { id } }', 200, '{"test_entities":[{"id":1},{"id":2},{"id":3}]}'],
            ['{ test_entities { name } }', 200, '{"test_entities":[{"name":"foo"},{"name":"bar"},{"name":"baz"}]}'],
            ['{ test_entities { name, parent { name } } }', 200, '{"test_entities":[{"name":"foo","parent":{"name":"Foo"}},{"name":"bar","parent":{"name":"Foo"}},{"name":"baz","parent":null}]}'],
            ['{ test_entities { name, parent { name, children { name } } } }', 200, '{"test_entities":[{"name":"foo","parent":{"name":"Foo","children":[{"name":"foo"},{"name":"bar"}]}},{"name":"bar","parent":{"name":"Foo","children":[{"name":"foo"},{"name":"bar"}]}},{"name":"baz","parent":null}]}'],
        ];
    }
}
