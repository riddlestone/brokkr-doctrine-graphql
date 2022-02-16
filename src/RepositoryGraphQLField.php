<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Exception;
use GraphQL\Language\AST\FieldNode;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Riddlestone\Brokkr\GraphQL\Fields\NeedsTypeManagerInterface;
use Riddlestone\Brokkr\GraphQL\Types\GraphQLTypeManager;

class RepositoryGraphQLField extends FieldDefinition implements NeedsTypeManagerInterface
{
    protected ?EntityRepository $repository = null;

    public function __construct(array $config, GraphQLTypeManager $typeManager)
    {
        parent::__construct(
            array_merge(
                [
                    'type' => fn () => Type::listOf(
                        $typeManager->get($this->getRepository()->getClassName())
                    ),
                    'resolve' =>
                        /**
                         * @param mixed $_rootValue
                         * @param mixed $_args
                         * @param mixed $_contextValue
                         * @param ResolveInfo $info
                         * @return array
                         */
                        fn ($_rootValue, $_args, $_contextValue, ResolveInfo $info): array => $this->resolve($info),
                ],
                $config
            )
        );
    }

    /**
     * @param EntityRepository $repository
     */
    public function setRepository(EntityRepository $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @return EntityRepository
     * @throws Exception
     */
    public function getRepository(): EntityRepository
    {
        if (!$this->repository) {
            throw new Exception('Repository not set');
        }
        return $this->repository;
    }

    /**
     * @throws Exception
     */
    protected function resolve(ResolveInfo $info): array
    {
        $queryBuilder = $this->getRepository()->createQueryBuilder($this->getTableAlias(0));
        $this->joinRequiredTables($queryBuilder, $info->fieldNodes[0]);
        assert(is_array($result = $queryBuilder->getQuery()->getResult()));
        return $result;
    }

    /**
     * Joins tables needed for eager loading of results based on the field selections in the GraphQL query
     */
    protected function joinRequiredTables(QueryBuilder $queryBuilder, FieldNode $node, int &$joinNumber = 0): void
    {
        if (!$node->selectionSet) {
            return;
        }
        $rootJoinNumber = $joinNumber;
        foreach ($node->selectionSet->selections as $child) {
            if (
                !$child instanceof FieldNode
                || !$child->selectionSet
                || $child->selectionSet->selections->count() == 0
            ) {
                continue;
            }
            $queryBuilder->leftJoin(
                $this->getTableAlias($rootJoinNumber) . '.' . $child->name->value,
                $this->getTableAlias(++$joinNumber)
            );
            $this->joinRequiredTables($queryBuilder, $child, $joinNumber);
        }
    }

    protected function getTableAlias(int $joinNumber): string
    {
        return 't_' . $joinNumber;
    }
}
