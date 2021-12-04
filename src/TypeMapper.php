<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

/**
 * Maps Doctrine field types to GraphQL types
 */
class TypeMapper
{
    /**
     * @param array{type: string} $doctrineMapping
     * @return string|null
     */
    public function getGraphQLType(array $doctrineMapping): ?string
    {
        switch ($doctrineMapping['type']) {
            case 'string':
            case 'bigint':
            case 'decimal':
            case 'guid':
            case 'blob':
                return 'string';
            case 'integer':
            case 'smallint':
                return 'int';
            case 'boolean':
                return 'boolean';
            case 'float':
                return 'float';
            default:
                return null;
        }
    }
}
