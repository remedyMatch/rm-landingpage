<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['preregister'])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    storeInCsv($name, $email);
    header('Location: index.php?preregister=success');
    exit;
}

function storeInCsv($name, $email)
{
    $file = 'pre-register.csv';
    $data = [
        'name' => $name,
        'email' => $email,
        'datum' => date('Y-m-d H:i:s')
    ];
    $fp = fopen($file, 'a');
    fputcsv($fp, $data);
    fclose($fp);
}