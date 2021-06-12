<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="contacts")
     * @ORM\JoinColumn(name="id_person", referencedColumnName="id")
     */
    private $person;

    /**
     * @var ContactType|null
     * @ORM\ManyToOne(targetEntity="ContactType")
     * @ORM\JoinColumn(name="id_contact_type", referencedColumnName="id")
     */
    private $contactType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return Contact
     */
    public function setPerson(?Person $person): Contact
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return ContactType|null
     */
    public function getContactType(): ?ContactType
    {
        return $this->contactType;
    }

    /**
     * @param ContactType|null $contactType
     * @return Contact
     */
    public function setContactType(?ContactType $contactType): Contact
    {
        $this->contactType = $contactType;
        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }
}
