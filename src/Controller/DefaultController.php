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
    private  $mentions = [
            [
                'title' => 'Bundesregierung',
                'url' => 'https://www.bundesregierung.de/breg-de/themen/coronavirus/hackathon-ehrung-1738080',
                'img' => '/assets/images/print/Die-Bundesregierung-Logo.png',
                'description' => 'Die Arbeit beginnt jetzt',
                'date' => '01.04.2020',
                
            ],
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
                'img' => '/assets/images/print/zukunft-krankenhaus-einkauf-logo.jpg',
                'description' => 'RemedyMatch bringt Bedarf an Schutzausrüstung und Spenden zusammen',
                'date' => '22.03.2020',
                
            ],
            [
                'title' => 'Tagespiegel',
                'url' => 'https://background.tagesspiegel.de/digitalisierung/hackathon-nun-startet-die-umsetzungsphase',
                'img' => '/assets/images/print/TagesspiegelBackground.jpg',
                'description' => 'Hackathon: Nun startet die Umsetzungsphase',
                'date' => '31.03.2020',
                
            ],
            [
                'title' => 'DIE Zeit',
                'url' => 'https://www.zeit.de/digital/internet/2020-03/hackathon-wirvsvirus-bundesregierung-gewinnerprojekte-ideen-umsetzung',
                'img' => '/assets/images/print/Zeit-Onlinepng.png',
                'description' => 'Die Corona-Hacks sind da',
                'date' => '31.03.2020',
                
            ],
            [
                'title' => 'Ärzteblatt',
                'url' => 'https://www.aerzteblatt.de/nachrichten/111528/Hackathon-foerdert-digitale-Loesungen-fuer-Krisenzeiten',
                'img' => '/assets/images/print/aerzteblatt-logo.png',
                'description' => 'Hackathon fördert digitale Lösungen für Krisenzeiten',
                'date' => '01.04.2020',
                
            ],
            [
                'title' => 'Business Insider',
                'url' => 'https://www.businessinsider.de/tech/hackathon-zur-corona-krise-das-sind-die-20-gewinner-des-wettbewerbs/',
                'img' => '/assets/images/print/Business-insider-logo.png',
                'description' => 'Hackathon zur Corona-Krise: Das sind die 20 Gewinner des Wettbewerbs',
                'date' => '01.04.2020',
                
            ],
            [
                'title' => 'Computerwoche',
                'url' => 'https://www.computerwoche.de/a/hackathon-gegen-die-coronavirus-krise,3548708',
                'img' => '/assets/images/print/computerwoche-logo.png',
                'description' => 'Hackathon gegen die Coronavirus-Krise',
                'date' => '01.04.2020',
                
            ],
            [
                'title' => 'MedicalMountains',
                'url' => 'https://medicalmountains.de/marktplatz/corona-drehscheibe/taskforces-fuer-die-medizintechnik/',
                'img' => '/assets/images/print/MM-drehscheibe-logo.png',
                'description' => 'Taskforces für die Medizintechnik',
                'date' => '03.04.2020',
                
            ],
             [
                'title' => 'BVMed',
                'url' => 'https://www.bvmed.de/de/technologien/hygieneprodukte/corona-informationen-des-bvmed-zu-praevention-und-umgang/_2-kurzarbeit-din-normen-kostenfrei-ausfuhren-efta-informationen',
                'img' => '/assets/images/print/BVMed-logo.png',
                'description' => 'Corona-Virus | Neue EU-Export-Anordnung | Förderungen und Kurzarbeit | Angebots-Abgabe | Betriebsablauf | Prävention',
                'date' => '27.03.2020',
                
            ]
           
            
        ];
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }
    function date_compare($element1, $element2) { 
        $datetime1 = strtotime($element1['date']); 
        $datetime2 = strtotime($element2['date']); 
        return  $datetime2 -$datetime1; 
    }  
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        usort($this->mentions, array($this,"date_compare")); 
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
                'title' => 'MySimpleShow',
                'url' => 'https://www.mysimpleshow.com/de/',
                'img' => '/assets/images/partners/simpleshow.png',
                'description' => 'Simpleshow stellt uns als Non-Profit Projekt den Premium-Account zur Verfügung.',
            ]
        ];

        
        
        return $this->render('index/index.html.twig', [
            'preregister' => $request->get('registered'),
            'partners' => $partners,
            'mentions' => $this->mentions,
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
            ->to(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->replyTo($request->get('email'))
            ->subject('Kontaktanfrage über RemedyMatch.io')
            ->htmlTemplate('emails/contact-us.twig')
            ->context([
                'from' => $request->get('name'),
                'emailAddress' => $request->get('email'),
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
     * @Route("/presse", name="presse")
     * @return Response
     */
    public function presse()
    {
        usort($this->mentions, array($this,"date_compare")); 
        return $this->render('press/presse.html.twig', ['mentions'=>$this->mentions]);
    }
    /**
     * @Route("/mail", name="mail")
     * @return Response
     */
    public function mail()
    {
        return $this->render('emails/account-confirm.html.twig');
    }
    /**
     * @Route("contactHR", name="contactHR")
     * @param Request $request
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function contactHR(Request $request)
    {
        $this->sendSlackNotification('Es gibt eine neue Bewerbung in den HR-Mails.');

        // prepare email
        $email = (new TemplatedEmail())
            ->from(new Address('info@remedymatch.io', 'RemedyMatch.io'))
            ->to(new Address('hr@remedymatch.io', 'RemedyMatch.io'))
            ->replyTo($request->get('email'))
            ->subject('Kontaktanfrage über RemedyMatch.io')
            ->htmlTemplate('emails/contact-us.twig')
            ->context([
                'from' => $request->get('name'),
                'emailAddress' => $request->get('email'),
                'message' => $request->get('message')
            ]);

        $this->mailer->send($email);

        return $this->redirectToRoute('jobs', ['mailSent' => 1]);
    }
    /**
     * @Route("/jobs", name="jobs")
     * @param Request $request
     * @return Response
     */
    public function jobs(Request $request)
    {
        return $this->render('jobs/jobs.html.twig',['emailSent' => $request->get('mailSent')]);
    }
}