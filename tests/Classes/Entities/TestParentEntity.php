<?php

namespace Riddlestone\Brokkr\DoctrineGraphQL\Test\Classes\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class TestParentEntity
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
     * @ORM\OneToMany(targetEntity="TestEntity", mappedBy="parent")
     */
    protected Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

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

    public function addChild(TestEntity $child): void
    {
        $this->children->add($child);
    }

    public function removeChild(TestEntity $child): void
    {
        $this->children->removeElement($child);
    }
}
