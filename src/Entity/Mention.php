<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MentionRepository")
 *
 * @Vich\Uploadable
 *
 * @UniqueEntity(fields={"title"})
 */
class Mention
{
    /**
     * @var UuidInterface|null
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     *
     * @Assert\NotBlank
     * @Assert\Url
     * @Assert\Length(max="1000")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1000)
     *
     * @Assert\NotBlank
     * @Assert\Url
     * @Assert\Length(max="1000")
     */
    private $urlGerman;

    /**
     * @Vich\UploadableField(mapping="mention_image", fileNameProperty="image.name", size="image.size", mimeType="image.mimeType", originalName="image.originalName", dimensions="image.dimensions")
     */
    private $imageFile;

    /**
     * @var EmbeddedFile
     *
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     */
    private $image;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isGerman;

    public function getUrlGerman(): ?string
    {
        return $this->urlGerman;
    }

    public function setUrlGerman(string $urlGerman): void
    {
        $this->urlGerman = $urlGerman;
    }

    public function isGerman(): ?bool
    {
        return $this->isGerman;
    }

    public function setIsGerman(bool $isGerman): void
    {
        $this->isGerman = $isGerman;
    }

    public function __construct()
    {
        $this->image = new EmbeddedFile();
    }

    public function getId(): ?UuidInterface
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;

        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }
}
