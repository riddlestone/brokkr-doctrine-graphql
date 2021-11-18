<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL;

use Doctrine\ORM\EntityRepository;
use Exception;
use GraphQL\Type\Definition\FieldDefinition;
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
                    'resolve' => fn () => $this->resolve(),
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
     * @return array
     * @throws Exception
     */
    protected function resolve(): array
    {
        return $this->getRepository()->findAll();
    }
}
