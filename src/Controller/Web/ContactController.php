<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Service\SlackNotifierService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

final class ContactController extends AbstractController
{
    private $mailer;
    private $slackNotifier;

    public function __construct(MailerInterface $mailer, SlackNotifierService $slackNotifier)
    {
        $this->mailer = $mailer;
        $this->slackNotifier = $slackNotifier;
    }

    /**
     * @Route("/contact", name="contact", methods={"POST"})
     *
     * @return RedirectResponse
     *
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request)
    {
        $this->slackNotifier->sendNotification('Es gibt eine neue Anfrage über das Kontaktformular.');

        // prepare email
        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->replyTo($request->get('email'))
            ->subject('Kontaktanfrage über RemedyMatch.io')
            ->htmlTemplate('emails/contact-us.twig')
            ->context([
                'from' => $request->get('name'),
                'emailAddress' => $request->get('email'),
                'message' => $request->get('message'),
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('web_index', ['mailSent' => 1]);
    }

    /**
     * @Route("contactHR", name="contactHR", methods={"POST"})
     *
     * @return RedirectResponse
     *
     * @throws TransportExceptionInterface
     */
    public function contactHR(Request $request)
    {
        $this->slackNotifier->sendNotification('Es gibt eine neue Bewerbung in den HR-Mails.');

        // prepare email
        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->replyTo($request->get('email'))
            ->subject('Kontaktanfrage über RemedyMatch.io')
            ->htmlTemplate('emails/contact-us.twig')
            ->context([
                'from' => $request->get('name'),
                'emailAddress' => $request->get('email'),
                'message' => $request->get('message'),
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('web_jobs', ['mailSent' => 1]);
    }
}
