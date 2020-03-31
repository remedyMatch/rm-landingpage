<?php

namespace App\Controller;

use JoliCode\Slack\ClientFactory;
use JoliCode\Slack\Exception\SlackErrorResponse;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $partners = [
            [
                'title' => 'FlowSquad',
                'url' => 'https://www.flowsquad.io/',
                'img' => '/assets/images/partners/flowsquad.png',
                'description' => 'Unterstützt uns in der Workflow-Automatisierung und Softwareentwicklung',
            ],
            [
                'title' => 'Slack',
                'url' => 'https://www.slack.com/',
                'img' => 'https://cdn.brandfolder.io/5H442O3W/as/pl546j-7le8zk-5guop3/Slack_RGB.png?width=300&height=123',
                'description' => 'Slack stellt uns als Non-Profit Projekt den Premium-Account zur Verfügung.',
            ],
            [
                'title' => 'Slack',
                'url' => 'https://www.slack.com/',
                'img' => '/assets/images/partners/simpleshow.png',
                'description' => 'Simpleshow unterstützt uns durch die kostenlose Bereitstellung des mysimpleshow Online-Video-Creator.',
            ]
        ];

        $mentions = [
            [
                'title' => 'Berliner Zeitung',
                'url' => 'https://www.berliner-zeitung.de/zukunft-technologie/1500-projekte-sind-beim-groesstem-hackathon-der-welt-entstanden-li.79615',
                'img' => '/assets/images/print/berliner-zeitung.png',
                'description' => '1500 Projekte sind beim größten Hackathon der Welt entstanden',
                'date' => '27.03.2020',
            ],
            [
                'title' => 'Zukunft Krankenhaus Einkauf',
                'url' => 'https://www.zukunft-krankenhaus-einkauf.de/2020/03/22/remedymatch-bringt-bedarf-an-schutzausr%C3%BCstung-und-spenden-zusammen/',
                'img' => '/assets/images/print/zukunft-krankenhaus-einkauf.png',
                'description' => 'RemedyMatch bringt Bedarf an Schutzausrüstung und Spenden zusammen',
                'date' => '22.03.2020',
            ]
        ];

        return $this->render('index/index.html.twig', [
            'preregister' => $request->get('registered'),
            'partners' => $partners,
            'mentions' => $mentions,
            'emailSent' => $request->get('mailSent'),
        ]);
    }

    /**
     * @Route("contact", name="contact")
     * @param Request $request
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request)
    {
        $this->sendSlackNotification('Es gibt eine neue Anfrage über das Kontaktformular.');

        // prepare email
        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address('mail@roman-allenstein.de', 'Roman Allenstein'))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Kontaktanfrage über RemedyMatch.io')
            ->htmlTemplate('emails/contact-us.twig')
            ->context([
                'from' => $request->get('name'),
                'message' => $request->get('message')
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('index', ['mailSent' => 1]);
    }

    /**
     * @param $message
     */
    private function sendSlackNotification($message)
    {
        $token = $this->getParameter('app.slack_token');
        $client = ClientFactory::create($token);
        try {
            // This method requires your token to have the scope "chat:write"
            $result = $client->chatPostMessage([
                'username' => 'remedybot',
                'channel' => 'sandkasten',
                'text' => $message,
            ]);
        } catch (SlackErrorResponse $e) {

        }
    }

    /**
     * @Route("/impressum", name="impressum")
     * @return Response
     */
    public function impressum()
    {
        return $this->render('index/impressum.html.twig');
    }

    /**
     * @Route("/datenschutz", name="datenschutz")
     * @return Response
     */
    public function datenschutz()
    {
        return $this->render('index/datenschutz.html.twig');
    }

    /**
     * @Route("/mail", name="mail")
     * @return Response
     */
    public function mail()
    {
        return $this->render('emails/account-confirm.html.twig');
    }
}