<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RelationRepository::class)
 */
class Relation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="id_person1", referencedColumnName="id")
     */
    private $person1;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(name="id_person2", referencedColumnName="id")
     */
    private $person2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var RelationType|null
     * @ORM\ManyToOne(targetEntity="RelationType")
     * @ORM\JoinColumn(name="id_relation_type", referencedColumnName="id")
     */
    private $relationType;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Person|null
     */
    public function getPerson1(): ?Person
    {
        return $this->person1;
    }

    /**
     * @param Person|null $person1
     * @return Relation
     */
    public function setPerson1(?Person $person1): Relation
    {
        $this->person1 = $person1;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPerson2(): ?Person
    {
        return $this->person2;
    }

    /**
     * @param Person|null $person2
     * @return Relation
     */
    public function setPerson2(?Person $person2): Relation
    {
        $this->person2 = $person2;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return RelationType|null
     */
    public function getRelationType(): ?RelationType
    {
        return $this->relationType;
    }

    /**
     * @param RelationType|null $relationType
     * @return Relation
     */
    public function setRelationType(?RelationType $relationType): Relation
    {
        $this->relationType = $relationType;
        return $this;
    }
}
