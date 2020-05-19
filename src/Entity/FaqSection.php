<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FaqSectionRepository")
 */
class FaqSection
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
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     */
    private $titleGerman;

    /**
     * @var Collection<FaqEntry>
     *
     * @ORM\OneToMany(targetEntity="FaqEntry", mappedBy="faqSection", cascade={"persist", "remove"})
     */
    private $faqEntries;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitleGerman(): ?string
    {
        return $this->titleGerman;
    }

    public function setTitleGerman(string $titleGerman): void
    {
        $this->titleGerman = $titleGerman;
    }

    public function addFaqEntry(FaqEntry $faqEntry): void
    {
        if (null === $this->faqEntries) {
            $this->faqEntries = new ArrayCollection();
        }

        $faqEntry->setFaqSection($this);
        $this->faqEntries->add($faqEntry);
    }

    public function removeFaqEntry(FaqEntry $faqEntry): void
    {
        $this->faqEntries->removeElement($faqEntry);
    }

    /**
     * @return Collection<FaqEntry>|null
     */
    public function getFaqEntries(): ?Collection
    {
        return $this->faqEntries;
    }
}
