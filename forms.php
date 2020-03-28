<?php


namespace RmLandingpage;

require 'vendor/autoload.php';

use JoliCode\Slack\ClientFactory;
use JoliCode\Slack\Exception\SlackErrorResponse;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendSlackNotification($message)
{
    $token = 'xoxb-1018373470342-1011928550819-0mwEER1Un4bKsD9ANw56hUxF';
    $client = ClientFactory::create($token);
    try {
        // This method requires your token to have the scope "chat:write"
        $result = $client->chatPostMessage([
            'username' => 'remedybot',
            'channel' => 'allgemein',
            'text' => $message,
        ]);
    } catch (SlackErrorResponse $e) {

    }
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

if (isset($_POST['preregister'])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    if(empty($name)|| empty($email)){
       header('Location: index.php');
        exit;
    }
    
    storeInCsv($name, $email);
    sendSlackNotification('Ein Benutzer hat sich für die Nutzung der App vormerken lassen.');
    header('Location: index.php?preregister=success#prereg');
    exit;
}


if (isset($_POST['submitted'])) {
    sendSlackNotification('Es gibt eine neue Anfrage über das Kontaktformular.');

    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $usernameSmtp = 'AKIARQLZ7MA7QJLDSQGW';
    $passwordSmtp = 'BCl+IT/NdHN7HJ23RolPTcaB/8hOosQ7wHZE6aFRJM3+';

    //$configurationSet = 'ConfigSet';
    $host = 'email-smtp.us-east-1.amazonaws.com';
    $port = 587;

    $sender = "noreply@remedymatch.dev";
    $senderName = "RemedyMatch";

    $subject = "Ihre Nachricht an das Team von RemedyMatch:" . $_POST["subject"];
    $recipient = 'remedymatch2020@gmx.de';

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
        
        $mail->AddReplyTo($email, 'Reply to '.$name);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyHtml;
        $mail->Send();

        header('Location: index.php?emailSent=success');
    } catch (\phpmailerException $e) {
        
        header('Location: index.php');
        exit;
        
    } catch (\Exception $e) {
        
        header('Location: index.php');
        exit;    

    }
}