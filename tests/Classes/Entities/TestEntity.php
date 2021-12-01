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
     */
    protected ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    protected ?string $name = null;

    /**
     * @ORM\ManyToOne(targetEntity="TestParentEntity", inversedBy="children")
     */
    protected ?TestParentEntity $parent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setParent(?TestParentEntity $parent): void
    {
        $this->parent && $this->parent->removeChild($this);
        $this->parent = $parent;
        $this->parent && $this->parent->addChild($this);
    }
}
