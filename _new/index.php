<?php
require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    # 'cache' => __DIR__ . '/cache',
]);

$preregister = isset($_GET['preregister']) && $_GET['preregister'] == 'success' ? '1' : '0';
$emailSent = isset($_GET['emailSent']) && $_GET['emailSent'] == 'success' ? '1' : '0';
echo $twig->render('index.html.twig', ['preregister' => $preregister, 'emailSent' => $emailSent]);