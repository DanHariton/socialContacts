<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @var Location|null
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="persons")
     * @ORM\JoinColumn(name="id_location", referencedColumnName="id")
     */
    private $location;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthDay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gender;

    /**
     * @var ArrayCollection|Contact[]
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="person")
     */
    private $contacts;

    /**
     * @var ArrayCollection|Meeting[]
     * @ORM\ManyToMany(targetEntity="Meeting", inversedBy="persons")
     * @ORM\JoinTable(name="persons_meetings")
     */
    private $meetings;


    /**
     * Person constructor.
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->meetings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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
     * @return Person
     */
    public function setLocation(?Location $location): Person
    {
        $this->location = $location;
        return $this;
    }

    public function getBirthDay(): ?\DateTimeInterface
    {
        return $this->birthDay;
    }

    public function setBirthDay(?\DateTimeInterface $birthDay): self
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return Person
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Contact[]|ArrayCollection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param Contact[]|ArrayCollection $contacts
     * @return Person
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * @param Contact $contact
     * @return $this
     */
    public function addContact($contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setPerson($this);
        }

        return $this;
    }

    /**
     * @param Contact $contact
     * @return $this
     */
    public function removeContact($contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            if ($contact->getPerson() === $this) {
                $contact->setPerson(null);
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
     * @return Person
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
        }

        return $this;
    }

    /**
     * @param Meeting $meeting
     * @return $this
     */
    public function removeMeeting($meeting): self
    {
        $this->meetings->removeElement($meeting);

        return $this;
    }
}
