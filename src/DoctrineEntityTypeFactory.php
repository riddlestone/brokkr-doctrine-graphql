<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Riddlestone\Brokkr\GraphQL\Types\GraphQLTypeManager;

class DoctrineEntityTypeFactory implements AbstractFactoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        return $entityManager->getMetadataFactory()->hasMetadataFor($requestedName);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): DoctrineEntityType
    {
        return new DoctrineEntityType(
            $requestedName,
            $container->get(EntityManager::class),
            $container->get(GraphQLTypeManager::class)
        );
    }
}
