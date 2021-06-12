<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
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
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $streetName;

    /**
     * @ORM\Column(type="integer")
     */
    private $streetNumber;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $longnitude;

    /**
     * @var ArrayCollection|Person[]
     * @ORM\OneToMany(targetEntity="Person", mappedBy="location")
     */
    private $persons;

    /**
     * @var ArrayCollection|Meeting[]
     * @ORM\OneToMany(targetEntity="Meeting", mappedBy="location")
     */
    private $meetings;

    /**
     * Location constructor.
     */
    public function __construct()
    {
        $this->persons = new ArrayCollection();
        $this->meetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLatitude(): ?int
    {
        return $this->latitude;
    }

    public function setLatitude(?int $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongnitude(): ?int
    {
        return $this->longnitude;
    }

    public function setLongnitude(?int $longnitude): self
    {
        $this->longnitude = $longnitude;

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
     * @return Location
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
    public function addContact($person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->setLocation($this);
        }

        return $this;
    }

    /**
     * @param Person $person
     * @return $this
     */
    public function removeContact($person): self
    {
        if ($this->persons->removeElement($person)) {
            if ($person->getLocation() === $this) {
                $person->setLocation(null);
            }
        }

        return $this;
    }

    /**
     * @return Meeting[]|ArrayCollection
     */
    public function getMeetings()
    {
        return $this->meetings;
    }

    /**
     * @param Meeting[]|ArrayCollection $meetings
     * @return Location
     */
    public function setMeetings($meetings)
    {
        $this->meetings = $meetings;
        return $this;
    }

    /**
     * @param Meeting $meeting
     * @return $this
     */
    public function addMeeting($meeting): self
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings[] = $meeting;
            $meeting->setLocation($this);
        }

        return $this;
    }

    /**
     * @param Meeting $meeting
     * @return $this
     */
    public function removeMeeting($meeting): self
    {
        if ($this->meetings->removeElement($meeting)) {
            if ($meeting->getLocation() === $this) {
                $meeting->setLocation(null);
            }
        }

        return $this;
    }
}
