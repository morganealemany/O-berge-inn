<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("events:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups("events:read")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups("events:read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128)
     * @Groups("events:read")
     */
    private $adress;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups("events:read")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="event", orphanRemoval=true)
     */
    private $participation;

    /**
     * @ORM\ManyToMany(targetEntity=Need::class, inversedBy="events")
     */
    private $need;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="event")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Need::class, mappedBy="event", orphanRemoval=true)
     */
    private $needs;

    /**
     * @ORM\OneToOne(targetEntity=Survey::class, mappedBy="event", cascade={"persist", "remove"})
     */
    private $survey;

    public function __construct()
    {
        $this->participation = new ArrayCollection();
        $this->need = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->needs = new ArrayCollection();
    }

      /**
     * toString
     * @return string
     */
    public function __toString(){
        return $this->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }
    
    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|participation[]
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(participation $participation): self
    {
        if (!$this->participation->contains($participation)) {
            $this->participation[] = $participation;
            $participation->setEvent($this);
        }

        return $this;
    }

    public function removeParticipation(participation $participation): self
    {
        if ($this->participation->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getEvent() === $this) {
                $participation->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|need[]
     */
    public function getNeed(): Collection
    {
        return $this->need;
    }

    public function addNeed(need $need): self
    {
        if (!$this->need->contains($need)) {
            $this->need[] = $need;
        }
        $need->setEvent($this);
        
        $this->needs->add($need);

        return $this;
    }

    public function removeNeed(need $need): self
    {
        $this->need->removeElement($need);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Need[]
     */
    public function getNeeds(): Collection
    {
        return $this->needs;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(Survey $survey): self
    {
        // set the owning side of the relation if necessary
        if ($survey->getEvent() !== $this) {
            $survey->setEvent($this);
        }

        $this->survey = $survey;

        return $this;
    }

}
