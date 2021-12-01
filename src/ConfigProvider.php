<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Riddlestone\Brokkr\GraphQL\Fields\GraphQLFieldProviderInterface;
use Riddlestone\Brokkr\GraphQL\Types\GraphQLTypeProviderInterface;

/**
 * Provides module configuration for Laminas' Config Aggregator
 */
class ConfigProvider implements GraphQLFieldProviderInterface, GraphQLTypeProviderInterface
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
            'graphql_fields' => $this->getGraphQLFieldConfig(),
            'graphql_types' => $this->getGraphQLTypeConfig(),
        ];
    }

    public function getDependencyConfig(): array
    {
        return [
            'factories' => [
                TypeMapper::class => InvokableFactory::class,
            ],
        ];
    }

    public function getGraphQLFieldConfig(): array
    {
        return [
            'abstract_factories' => [
                RepositoryGraphQLFieldFactory::class,
            ],
        ];
    }

    public function getGraphQLTypeConfig(): array
    {
        return [
            'abstract_factories' => [
                DoctrineEntityTypeFactory::class,
            ],
        ];
    }
}