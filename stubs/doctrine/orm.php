<?php

namespace Doctrine\ORM\Mapping;

use Doctrine\Persistence\Mapping\ClassMetadata;

/**
 * Need this until ClassMetadataInfo declares the fields used in association mappings itself
 *
 * @psalm-type AssociationMapping = array{
 *   fieldName: string,
 *   targetEntity: string,
 *   sourceEntity?: class-string,
 *   mappedBy?: string,
 *   inversedBy?: string,
 *   isOwningSide?: bool,
 *   type: int,
 *   cascade: list<"persist"|"remove"|"detach"|"merge"|"refresh"|"all">,
 *   isCascadeRemove?: bool,
 *   isCascadePersist?: bool,
 *   isCascadeRefresh?: bool,
 *   isCascadeMerge?: bool,
 *   isCascadeDetach?: bool,
 *   orderBy: array<string,"asc"|"desc">,
 *   fetch: null|self::FETCH_EAGER|self::FETCH_LAZY,
 *   joinTable?: array{
 *     name: string,
 *     joinColumns: array<string,string>,
 *     inverseJoinColumns: array<string,string>
 *   },
 *   indexBy?: string,
 *   originalField?: string,
 *   originalClass?: class-string,
 *   orphanRemoval?: bool
 * }
 */
class ClassMetadataInfo implements ClassMetadata
{
    /**
     * @psalm-var array<string, AssociationMapping>
     */
    public $associationMappings = [];

    /**
     * @param string $fieldName
     * @psalm-return AssociationMapping
     * @throws MappingException
     */
    public function getAssociationMapping($fieldName) {}

    /**
     * @psalm-return array<string, AssociationMapping>
     */
    public function getAssociationMappings() {}
}
