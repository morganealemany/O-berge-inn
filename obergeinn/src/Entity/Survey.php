<?php

namespace App\Entity;

use App\Repository\SurveyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @ORM\Entity(repositoryClass=SurveyRepository::class)
 */
class Survey
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=Event::class, inversedBy="survey", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity=SurveyResponses::class, mappedBy="survey", orphanRemoval=true)
     */
    private $survey_responses;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->survey_responses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection|SurveyResponses[]
     */
    public function getSurveyResponses(): Collection
    {
        return $this->survey_responses;
    }

    public function addSurveyResponse(SurveyResponses $surveyResponse): self
    {
        if (!$this->survey_responses->contains($surveyResponse)) {
            $this->survey_responses[] = $surveyResponse;
            $surveyResponse->setSurvey($this);
        }

        return $this;
    }

    public function removeSurveyResponse(SurveyResponses $surveyResponse): self
    {
        if ($this->survey_responses->removeElement($surveyResponse)) {
            // set the owning side to null (unless already changed)
            if ($surveyResponse->getSurvey() === $this) {
                $surveyResponse->setSurvey(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
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
}
