<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Riddlestone\Brokkr\GraphQL\Types\GraphQLTypeManager;

class DoctrineEntityType extends ObjectType
{
    protected GraphQLTypeManager $typeManager;

    public function __construct(string $entityClass, EntityManager $entityManager, GraphQLTypeManager $typeManager)
    {
        $metadata = $entityManager->getClassMetadata($entityClass);

        parent::__construct([
            'fields' => array_merge(
                $this->getFieldsFromMetaData($metadata, $typeManager),
                $this->getRelationshipsFromMetaData($metadata)
            ),
        ]);
    }

    protected function getFieldsFromMetaData(ClassMetadata $metadata, GraphQLTypeManager $typeManager): array
    {
        $fields = [];
        foreach ($metadata->getFieldNames() as $fieldName) {
            try {
                $mapping = $metadata->getFieldMapping($fieldName);
            } catch (MappingException $e) {
                continue;
            }
            $type = $this->mapType($mapping['type']);
            if ($type) {
                $fields[$fieldName] = [
                    'type' => fn() => $typeManager->get($type),
                    'resolve' => fn($entity) => $metadata->getFieldValue($entity, $fieldName),
                ];
            }
        }
        return $fields;
    }

    protected function getRelationshipsFromMetaData(ClassMetadata $metadata): array
    {
        return [];
    }

    protected function mapType(string $doctrineType): ?string
    {
        return [
                'string' => 'string',
                'integer' => 'int',
                'smallint' => 'int',
                'bigint' => 'string',
                'boolean' => 'boolean',
                'decimal' => 'string',
                'text' => 'string',
                'float' => 'float',
                'guid' => 'string',
                'blob' => 'string',
            ][$doctrineType] ?? null;
    }
}
