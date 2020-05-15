<?php

declare(strict_types=1);

namespace App\Form\Admin\Handler;

final class InvitationModel
{
    /**
     * @Assert\NotBlank
     *
     * @var string
     */
    public $email;

    /**
     * @Assert\NotBlank
     *
     * @var array
     */
    public $roles;
}
