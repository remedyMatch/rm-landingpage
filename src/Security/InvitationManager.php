<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Invitation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InvitationManager
{
    public const TIME_UNTIL_INVALID = '+7 days';

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    public function invite(string $email, array $roles): void
    {
        $invitation = new Invitation();

        $invitation->setEmail($email);
        $invitation->setRoles($roles);

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();

        $link = $this->urlGenerator->generate('admin_security_register', [
            'id' => $invitation->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@remedymatch.eu', 'RemedyMatch.io'))
            ->to(new Address($invitation->getEmail()))
            ->subject('Einladung Administration RemedyMatch Website')
            ->textTemplate('emails/admin/invitation.text.twig')
            ->context([
                'link' => $link,
                'valid_until' => $invitation->getExpiresAt(),
            ]);

        $this->mailer->send($email);
    }

    public function isInvitationValid(Invitation $invitation): bool
    {
        $now = new \DateTime();

        return $now < $invitation->getExpiresAt();
    }
}
