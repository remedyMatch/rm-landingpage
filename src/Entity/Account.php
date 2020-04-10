<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("email")
 */
class Account
{
    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $lastname;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max="255")
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $street;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=10)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="10")
     */
    private $housenumber;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $zipcode;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(max="255")
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(max="255")
     */
    private $company;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(max="255")
     */
    private $token;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $verifiedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $reviewedAt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Length(max="255")
     */
    private $reviewer;

    /**
     * @var bool|null
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\NotNull
     */
    private $isRejected = false;

    /**
     * @var float|null
     *
     * @ORM\Column(type="decimal", precision=2, scale=1, nullable=true)
     */
    private $score;

    /**
     * @var string|null
     *
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->setIsRejected(false);
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(?float $score): void
    {
        $this->score = $score;
    }

    public function getIsRejected(): ?bool
    {
        return $this->isRejected;
    }

    public function setIsRejected(?bool $isRejected): void
    {
        $this->isRejected = $isRejected;
    }

    public function getReviewedAt(): ?\DateTime
    {
        return $this->reviewedAt;
    }

    public function setReviewedAt(?\DateTime $reviewedAt): void
    {
        $this->reviewedAt = $reviewedAt;
    }

    public function getReviewer(): ?string
    {
        return $this->reviewer;
    }

    public function setReviewer(?string $reviewer): void
    {
        $this->reviewer = $reviewer;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getVerifiedAt(): ?\DateTime
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTime $verifiedAt): void
    {
        $this->verifiedAt = $verifiedAt;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getHousenumber(): ?string
    {
        return $this->housenumber;
    }

    public function setHousenumber(?string $housenumber): void
    {
        $this->housenumber = $housenumber;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        if ('' === $type) {
            $type = null;
        }
        $this->type = $type;
    }

    public function getCompany(): ?string
    {
        if ('' === $this->company) {
            $this->company = null;
        }

        return $this->company;
    }

    public function setCompany(?string $company): void
    {
        $this->company = $company;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}
