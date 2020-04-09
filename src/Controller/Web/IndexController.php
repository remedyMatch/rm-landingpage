<?php

namespace App\Controller\Web;

use App\StaticData\Mentions;
use App\StaticData\Partners;
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

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $mentions = Mentions::DATA;
        usort($mentions, array($this, "date_compare"));


        return $this->render('index/index.html.twig', [
            'preregister' => $request->get('registered'),
            'partners' => Partners::DATA,
            'mentions' => $mentions,
            'emailSent' => $request->get('mailSent'),
        ]);
    }
}