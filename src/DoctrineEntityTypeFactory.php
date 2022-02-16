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
        assert(($entityManager = $container->get(EntityManager::class)) instanceof EntityManager);
        assert(($typeManager = $container->get(GraphQLTypeManager::class)) instanceof GraphQLTypeManager);
        assert(($typeMapper = $container->get(TypeMapper::class)) instanceof TypeMapper);
        return new DoctrineEntityType(
            $requestedName,
            $entityManager,
            $typeManager,
            $typeMapper
        );
    }
}
