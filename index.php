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
        'img' => 'https://www.flowsquad.io/static/157573608aa7d0ebfd770b700745cb0c/159a2/logo-head.png',
        'description' => '',
    ]
];
echo $twig->render('index.html.twig', [
    'preregister' => $preregister,
    'emailSent' => $emailSent,
    'partners' => $partners
]);