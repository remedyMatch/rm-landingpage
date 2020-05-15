<?php

declare(strict_types=1);

namespace App\Form\Admin;

use Symfony\Component\Validator\Constraints as Assert;

final class InviteModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     *
     * @var string
     */
    public $email = '';

    /**
     * @Assert\NotBlank
     *
     * @var array
     */
    public $roles = [];
}
