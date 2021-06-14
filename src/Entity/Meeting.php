<?php

namespace App\Entity;

use App\Repository\MeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeetingRepository::class)
 */
class Meeting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $duration;

    /**
     * @var Location|null
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="meetings")
     * @ORM\JoinColumn(name="id_location", referencedColumnName="id")
     */
    private $location;

    /**
     * @var ArrayCollection|Person[]
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="meetings")
     */
    private $persons;

    /**
     * Meeting constructor.
     */
    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Meeting
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(?\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location|null $location
     * @return Meeting
     */
    public function setLocation(?Location $location): Meeting
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return Person[]|ArrayCollection
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * @param Person[]|ArrayCollection $persons
     * @return Meeting
     */
    public function setPersons($persons)
    {
        $this->persons = $persons;
        return $this;
    }

    /**
     * @param Person $person
     * @return $this
     */
    public function addPerson($person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
        }

        return $this;
    }

    /**
     * @param Person $person
     * @return $this
     */
    public function removePerson($person): self
    {
        $this->persons->removeElement($person);

        return $this;
    }
}
