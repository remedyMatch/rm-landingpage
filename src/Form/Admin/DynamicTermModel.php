<?php

declare(strict_types=1);

namespace App\Form\Admin;

use App\Entity\DynamicTerm;
use Symfony\Component\Validator\Constraints as Assert;

final class DynamicTermModel
{
    /**
     * @Assert\NotBlank
     *
     * @var string
     */
    public $placeholder = '';

    /**
     * @Assert\NotBlank
     *
     * @var string
     */
    public $value = '';

    public static function fromEntity(DynamicTerm $dynamicTerm): self
    {
        $self = new self();
        $self->placeholder = $dynamicTerm->getPlaceholder();
        $self->value = $dynamicTerm->getValue();

        return $self;
    }
}
