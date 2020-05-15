<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DynamicTermRepository")
 */
class DynamicTerm
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
     * @ORM\Column(unique=true)
     */
    private $placeholder;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $value;

    public function __construct(string $placeholder, string $value)
    {
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function update(string $placeholder, string $value): void
    {
        $this->placeholder = $placeholder;
        $this->value = $value;
    }
}
