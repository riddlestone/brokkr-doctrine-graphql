<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\MappingException;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Riddlestone\Brokkr\GraphQL\Types\GraphQLTypeManager;

class DoctrineEntityType extends ObjectType
{
    public function __construct(
        string $entityClass,
        EntityManager $entityManager,
        GraphQLTypeManager $typeManager,
        TypeMapper $typeMapper
    ) {
        $metadata = $entityManager->getClassMetadata($entityClass);

        parent::__construct([
            'name' => $entityClass,
            'fields' => array_merge(
                $this->getFieldsFromMetaData($metadata, $typeManager, $typeMapper),
                $this->getAssociationsFromMetaData($metadata, $typeManager)
            ),
        ]);
    }

    protected function getFieldsFromMetaData(
        ClassMetadata $metadata,
        GraphQLTypeManager $typeManager,
        TypeMapper $typeMapper
    ): array
    {
        $fields = [];
        foreach ($metadata->getFieldNames() as $fieldName) {
            try {
                $mapping = $metadata->getFieldMapping($fieldName);
            } catch (MappingException $e) {
                continue;
            }
            $type = $typeMapper->getGraphQLType($mapping);
            if ($type) {
                $fields[$fieldName] = [
                    'type' => fn(): string => $typeManager->get($type),
                    'resolve' =>
                        /**
                         * @param mixed $entity
                         * @return mixed
                         */
                        fn($entity) => $metadata->getFieldValue($entity, $fieldName),
                ];
            }
        }
        return $fields;
    }

    protected function getAssociationsFromMetaData(
        ClassMetadata $metadata,
        GraphQLTypeManager $typeManager
    ): array
    {
        $fields = [];
        foreach ($metadata->getAssociationMappings() as $associationMapping) {
            $fields[$associationMapping['fieldName']] = [
                'type' => function() use ($typeManager, $associationMapping): Type {
                    $type = $typeManager->get($associationMapping['targetEntity']);
                    if ($associationMapping['type'] & ClassMetadataInfo::TO_MANY) {
                        $type = Type::listOf($type);
                    }
                    return $type;
                },
                'resolve' =>
                    /**
                     * @param mixed $entity
                     * @return mixed
                     */
                    fn($entity) => $metadata->getFieldValue($entity, $associationMapping['fieldName']),
            ];
        }
        return $fields;
    }
}
