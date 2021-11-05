<?php

namespace App\Entity;

use App\Repository\SurveyChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SurveyChoiceRepository::class)
 */
class SurveyChoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $choice;

    /**
     * @ORM\ManyToOne(targetEntity=SurveyResponses::class, inversedBy="surveyChoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $survey_responses;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="surveyChoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoice(): ?int
    {
        return $this->choice;
    }

    public function setChoice(int $choice): self
    {
        $this->choice = $choice;

        return $this;
    }

    public function getSurveyResponses(): ?SurveyResponses
    {
        return $this->survey_responses;
    }

    public function setSurveyResponses(?SurveyResponses $survey_responses): self
    {
        $this->survey_responses = $survey_responses;

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
