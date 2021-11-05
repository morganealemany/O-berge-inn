<?php

namespace App\Entity;

use App\Repository\SurveyResponsesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SurveyResponsesRepository::class)
 */
class SurveyResponses
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $response;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_responses;

    /**
     * @ORM\ManyToOne(targetEntity=Survey::class, inversedBy="survey_responses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey;

    /**
     * @ORM\OneToMany(targetEntity=SurveyChoice::class, mappedBy="survey_responses", orphanRemoval=true)
     */
    private $surveyChoices;

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
        $this->surveyChoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(): ?\DateTimeInterface
    {
        return $this->response;
    }

    public function setResponse(\DateTimeInterface $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getNbResponses(): ?int
    {
        return $this->nb_responses;
    }

    public function setNbResponses(?int $nb_responses): self
    {
        $this->nb_responses = $nb_responses;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

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
            $surveyChoice->setSurveyResponses($this);
        }

        return $this;
    }

    public function removeSurveyChoice(SurveyChoice $surveyChoice): self
    {
        if ($this->surveyChoices->removeElement($surveyChoice)) {
            // set the owning side to null (unless already changed)
            if ($surveyChoice->getSurveyResponses() === $this) {
                $surveyChoice->setSurveyResponses(null);
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
