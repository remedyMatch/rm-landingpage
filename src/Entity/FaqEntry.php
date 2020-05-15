<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class FaqEntry
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     */
    private $questionGerman;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     */
    private $answer;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     */
    private $answerGerman;

    /**
     * @var FaqSection
     *
     * @ORM\ManyToOne(targetEntity="FaqSection", inversedBy="faqEntries")
     */
    private $faqSection;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function getQuestionGerman(): ?string
    {
        return $this->questionGerman;
    }

    public function setQuestionGerman(string $questionGerman): void
    {
        $this->questionGerman = $questionGerman;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    public function getAnswerGerman(): ?string
    {
        return $this->answerGerman;
    }

    public function setAnswerGerman(string $answerGerman): void
    {
        $this->answerGerman = $answerGerman;
    }

    public function getFaqSection(): ?FaqSection
    {
        return $this->faqSection;
    }

    public function setFaqSection(FaqSection $faqSection): void
    {
        $this->faqSection = $faqSection;
    }
}
