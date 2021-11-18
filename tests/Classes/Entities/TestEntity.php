<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL\Test\Classes\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TestEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int|null
     */
    public ?int $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    public string $name;
}
