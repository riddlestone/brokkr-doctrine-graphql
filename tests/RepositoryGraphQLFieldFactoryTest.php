<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL\Test;

use Laminas\Test\PHPUnit\Controller\AbstractControllerTestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Riddlestone\Brokkr\DoctrineGraphQL\RepositoryGraphQLFieldFactory;
use Riddlestone\Brokkr\DoctrineGraphQL\Test\Classes\Entities\TestEntity;
use stdClass;

class RepositoryGraphQLFieldFactoryTest extends AbstractControllerTestCase
{
    protected function setUp(): void
    {
        $this->setApplicationConfig(
            require __DIR__ . '/test-application/config/application.php'
        );
        parent::setUp();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @covers \Riddlestone\Brokkr\DoctrineGraphQL\RepositoryGraphQLFieldFactory::canCreate
     */
    public function testCanCreate()
    {
        $factory = new RepositoryGraphQLFieldFactory();
        $this->assertTrue($factory->canCreate($this->getApplicationServiceLocator(), TestEntity::class));
        $this->assertFalse($factory->canCreate($this->getApplicationServiceLocator(), stdClass::class));
    }
}
