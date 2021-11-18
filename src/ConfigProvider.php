<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

use Riddlestone\Brokkr\GraphQL\Fields\GraphQLFieldProviderInterface;
use Riddlestone\Brokkr\GraphQL\Types\GraphQLTypeProviderInterface;

class ConfigProvider implements GraphQLFieldProviderInterface, GraphQLTypeProviderInterface
{
    public function __invoke(): array
    {
        return [
            'graphql_fields' => $this->getGraphQLFieldConfig(),
            'graphql_types' => $this->getGraphQLTypeConfig(),
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