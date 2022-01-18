<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="question")
     */
    private $ad;

    /**
     * @ORM\OneToOne(targetEntity=Answer::class, mappedBy="question", cascade={"persist", "remove"})
     */
    private $answer;

    public function __construct()
    {
        $this->ad = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection|Ad[]
     */
    public function getAd(): Collection
    {
        return $this->ad;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ad->contains($ad)) {
            $this->ad[] = $ad;
            $ad->setQuestion($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ad->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getQuestion() === $this) {
                $ad->setQuestion(null);
            }
        }

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(Answer $answer): self
    {
        // set the owning side of the relation if necessary
        if ($answer->getQuestion() !== $this) {
            $answer->setQuestion($this);
        }

        $this->answer = $answer;

        return $this;
    }
}
