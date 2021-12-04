<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\Mapping\MappingException;
use Exception;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Riddlestone\Brokkr\GraphQL\Types\GraphQLTypeManager;

class RepositoryGraphQLFieldFactory implements AbstractFactoryInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        try {
            $entityManager->getMetadataFactory()->getMetadataFor($requestedName);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RepositoryGraphQLField
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RepositoryGraphQLField
    {
        if (!class_exists($requestedName)) {
            throw new ServiceNotFoundException(sprintf('"%s" is not a known entity class', $requestedName));
        }
        $field = new RepositoryGraphQLField($options ?? [], $container->get(GraphQLTypeManager::class));
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        $field->setRepository($entityManager->getRepository($requestedName));
        return $field;
    }
}
