<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

/**
 * Maps Doctrine field types to GraphQL types
 */
class TypeMapper
{
    public function getGraphQLType($doctrineMapping): ?string
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
