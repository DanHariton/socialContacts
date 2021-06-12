<?php

namespace App\Entity;

use App\Repository\ContactTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactTypeRepository::class)
 */
class ContactType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $validationRegexp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValidationRegexp(): ?string
    {
        return $this->validationRegexp;
    }

    public function setValidationRegexp(?string $validationRegexp): self
    {
        $this->validationRegexp = $validationRegexp;

        return $this;
    }
}
