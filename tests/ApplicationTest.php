<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL\Test;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use Laminas\Http\Request;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Riddlestone\Brokkr\DoctrineGraphQL\Test\Classes\Entities\TestEntity;

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
        foreach(['foo', 'bar', 'baz'] as $name) {
            $entity = new TestEntity();
            $entity->name = $name;
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
        ];
    }
}
