<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

/**
 * Provides module integration for Laminas' Module Manager
 */
class Module
{
    public function getConfig(): array
    {
        $configProvider = new ConfigProvider();
        return [
            'service_manager' => $configProvider->getDependencyConfig(),
            'graphql_fields' => $configProvider->getGraphQLFieldConfig(),
            'graphql_types' => $configProvider->getGraphQLTypeConfig(),
        ];
    }
}
