<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="user", orphanRemoval=true)
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity=Participation::class, mappedBy="user", orphanRemoval=true)
     */
    private $participation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Assignation::class, mappedBy="user", orphanRemoval=true)
     */
    private $assignations;

    /**
     * @ORM\OneToMany(targetEntity=SurveyChoice::class, mappedBy="user", orphanRemoval=true)
     */
    private $surveyChoices;

    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->participation = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->assignations = new ArrayCollection();
        $this->surveyChoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event[] = $event;
            $event->setUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->event->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getUser() === $this) {
                $event->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(Participation $participation): self
    {
        if (!$this->participation->contains($participation)) {
            $this->participation[] = $participation;
            $participation->setUser($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): self
    {
        if ($this->participation->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getUser() === $this) {
                $participation->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * toString
     * @return string
     */
    public function __toString(){
        return $this->getUserIdentifier();
    }

    // I defined this function because of this error "Object of class App/Entity/User could not be converted to String
    // But i don't know if there will be consequences thereafter.


      
     /**
      * Undocumented function
      *
      * @return Collection|Assignation[]
      */
    public function getAssignations(): Collection
    {
        return $this->assignations;
    }

    public function addAssignation(Assignation $assignation): self
    {
        if (!$this->assignations->contains($assignation)) {
            $this->assignations[] = $assignation;
            $assignation->setUser($this);
        }

        return $this;
    }

    public function removeAssignation(Assignation $assignation): self
    {
        if ($this->assignations->removeElement($assignation)) {
            // set the owning side to null (unless already changed)
            if ($assignation->getUser() === $this) {
                $assignation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SurveyChoice[]
     */
    public function getSurveyChoices(): Collection
    {
        return $this->surveyChoices;
    }

    public function addSurveyChoice(SurveyChoice $surveyChoice): self
    {
        if (!$this->surveyChoices->contains($surveyChoice)) {
            $this->surveyChoices[] = $surveyChoice;
            $surveyChoice->setUser($this);
        }

        return $this;
    }

    public function removeSurveyChoice(SurveyChoice $surveyChoice): self
    {
        if ($this->surveyChoices->removeElement($surveyChoice)) {
            // set the owning side to null (unless already changed)
            if ($surveyChoice->getUser() === $this) {
                $surveyChoice->setUser(null);
            }
        }

        return $this;
    }
}

