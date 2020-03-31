<?php

namespace App\Controller;

use JoliCode\Slack\ClientFactory;
use JoliCode\Slack\Exception\SlackErrorResponse;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
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
     * @Route("register", name="register")
     * @param Request $request
     * @return RedirectResponse
     */
    public function register(Request $request)
    {
        $name = $request->get('name');
        $email = $request->get('email');

        if (empty($name) || empty($email)) {
            return $this->redirectToRoute('index');
        }

        $this->storeInCsv($name, $email);
        $this->sendSlackNotification('Ein Benutzer hat sich für die Nutzung der App vormerken lassen.');

        return $this->redirectToRoute('index', ['registered' => 1]);
    }

    private function storeInCsv($name, $email)
    {
        $file = __DIR__ . '/../../pre-register.csv';
        $data = [
            'name' => $name,
            'email' => $email,
            'datum' => date('Y-m-d H:i:s')
        ];
        $fp = fopen($file, 'a');
        fputcsv($fp, $data);
        fclose($fp);
    }

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
     * @Route("contact", name="contact")
     * @param Request $request
     * @return RedirectResponse
     */
    public function contact(Request $request)
    {
        $this->sendContactMail($request);
        return $this->redirectToRoute('index', ['mailSent' => 1]);
    }

    /**
     * @param Request $request
     * @return Response
     * @todo Replace by Symfony mailer
     */
    private function sendContactMail(Request $request)
    {
        $this->sendSlackNotification('Es gibt eine neue Anfrage über das Kontaktformular.');

        $name = $request->get('name');
        $email = $request->get('email');
        $message = $request->get('message');

        $usernameSmtp = 'AKIARQLZ7MA7QJLDSQGW';
        $passwordSmtp = 'BCl+IT/NdHN7HJ23RolPTcaB/8hOosQ7wHZE6aFRJM3+';

        //$configurationSet = 'ConfigSet';
        $host = 'email-smtp.us-east-1.amazonaws.com';
        $port = 587;

        $sender = "noreply@remedymatch.dev";
        $senderName = "RemedyMatch";

        $subject = "Ihre Nachricht an das Team von RemedyMatch";
        $recipient = 'info@remedymatch.io';

        $bodyHtml = '<html>
  <body>
  <h1>Nachricht an das Team von RemedyMatch</h1>
   
  <p>Folgende Frage wurde über das Kontaktformular gestellt:</p>
  
  ' . $message . '
  
  <p> Die Kontaktdaten sind: 
  </br> Name: ' . $name . ' </br>
  EMail: ' . $email . '</p>
  <p>Diese E-Mail wurde automatisch erstellt, bitte antworten Sie nicht auf diese Email.</p>
   
  </body>
  </html>';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->setFrom($sender, $senderName);
            $mail->Username = $usernameSmtp;
            $mail->Password = $passwordSmtp;
            $mail->Host = $host;
            $mail->Port = $port;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->CharSet = 'utf-8';
            if (!empty($configurationSet)) {
                $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);
            }
            $mail->addAddress($recipient);
            $mail->AddReplyTo($email, 'Reply to ' . $name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $bodyHtml;
            $mail->Send();

            return $this->forward('index', ['emailSent' => 1]);
        } catch (\Exception $e) {
            return $this->forward('index', ['emailSent' => 0]);
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