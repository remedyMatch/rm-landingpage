<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    # 'cache' => __DIR__ . '/cache',
]);

$preregister = isset($_GET['preregister']) && $_GET['preregister'] == 'success' ? '1' : '0';
$emailSent = isset($_GET['emailSent']) && $_GET['emailSent'] == 'success' ? '1' : '0';

$partners = [
    [
        'title' => 'FlowSquad',
        'url' => 'https://www.flowsquad.io/',
        'img' => '/assets/images/partners/flowsquad.png',
        'description' => 'Unterstützt uns in der Workflow-Automatisierung und Softwareentwicklung',
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

echo $twig->render('index.html.twig', [
    'preregister' => $preregister,
    'emailSent' => $emailSent,
    'partners' => $partners,
    'mentions' => $mentions
]);