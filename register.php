<?php
namespace RmLandingpage;

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function storeInCsvPrivate($firstName, $lastName, $email, $street, $houseNumber, $zipCode, $town, $tel, $hash)
{
    $file = 'registerPrivate.csv';
    $data = [
        'firstname' => $firstName,
        'lastname' => $lastName,
        'email' => $email,
        'street' => $street,
        'houseNumber' => $houseNumber,
        'zipCode' => $zipCode,
        'town' => $town,
        'tel' => $tel,
        'hash' => $hash,
        'datum' => date('Y-m-d H:i:s')
    ];
    $fp = fopen($file, 'a');
    fputcsv($fp, $data);
    fclose($fp);
}

function storeInCsvOrg($firstName, $lastName, $email, $street, $houseNumber, $zipCode, $town, $tel, $hash,$orgKat,$orgName)
{
    $file = 'registerInstitute.csv';
    $data = [
        'firstname' => $firstName,
        'lastname' => $lastName,
        'email' => $email,
        'street' => $street,
        'houseNumber' => $houseNumber,
        'zipCode' => $zipCode,
        'town' => $town,
        'tel' => $tel,
        'orgName' => $orgName,
        'orgKat' => $orgKat,
        'hash' => $hash,
        'datum' => date('Y-m-d H:i:s')
    ];
    $fp = fopen($file, 'a');
    fputcsv($fp, $data);
    fclose($fp);
}
function sendMail($email,$firstname,$lastname, $orgName = NULL){
   
    $usernameSmtp = 'AKIARQLZ7MA7QJLDSQGW';
    $passwordSmtp = 'BCl+IT/NdHN7HJ23RolPTcaB/8hOosQ7wHZE6aFRJM3+';

    //$configurationSet = 'ConfigSet';
    $host = 'email-smtp.us-east-1.amazonaws.com';
    $port = 587;

    $sender = "noreply@remedymatch.io";
    $senderName = "RemedyMatch";

    $subject = "Bitte bestätige deine Registrierung bei RemedyMatch";
    $recipient = $email;
    if($orgName == NULL){
        $greetings ="Sehr geehrte/r Frau/ Herr " .$lastname ."<p> <p> Vielen Dank für Ihre Registrierung bei RemedyMatch.</p>"; 
    } else{
        $greetings ="<p>Sehr geehrte/r Frau/ Herr " .$lastname ."</p> <p> Vielen Dank für Ihre Registrierung ihrer Organisation ".$orgName . " bei RemedyMatch.</p>" ; 
    }
    
    $bodyHtml = '<html>
      <body>
      <h1>Ihre Registrierung bei RemedyMatch</h1>'
      .$greetings.'
      <p> Bitte geben Sie uns etwas Zeit unser Team überprüft Ihre Daten und melden uns bei Ihnen, sobald die Freischaltung abgeschlossen ist, dies kann bis zu 24h dauern.</p>
      
      <p> Vielen Dank für Ihre Geduld </p>
      <p> Ihr Team von RemedyMatch </p>
      <p> <small>Diese E-Mail wurde automatisch erstellt, bitte antworten Sie nicht auf diese Email.</small></p>
       
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
        
        $mail->AddReplyTo($email, 'Reply to info@remedymatch.io');
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyHtml;
        $mail->Send();
        header('Location: index.php?register=success');
        exit;
       
    } catch (\phpmailerException $e) {
       
       
    } catch (\Exception $e) {
        
    }
    
}
if (isset($_POST['firstName'])) { 
    
    $private = true;
    //Get userinformations
    $firstname = utf8_decode($_POST["firstName"]);
    $lastname  = utf8_decode($_POST["lastName"]);
    $email = utf8_decode($_POST["email"]);
    
    $street = utf8_decode($_POST["street"]);
    $houseNumber = utf8_decode($_POST["houseNumber"]);
    $zipCode = utf8_decode($_POST["zipCode"]);
    $town = utf8_decode($_POST["town"]);
    $tel = utf8_decode($_POST["tel"]);
    $password = utf8_decode($_POST["password"]);
    
    if($_POST["kind"] == 'institute')
    {
       $orgName = utf8_decode($_POST["orgName"]);
       $orgKat = utf8_decode($_POST["orgKategorie"]);
       $private = false;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    if(empty($firstname)|| empty($email)){
       header('Location: index.php');
        exit;
    }
    
    if($private){
        storeInCsvPrivate($firstname, $lastname, $email, $street, $houseNumber, $zipCode, $town, $tel, $hash);
        sendMail($email,$firstname,$lastname);
        
    } else{
        storeInCsvOrg($firstname, $lastname, $email, $street, $houseNumber, $zipCode, $town, $tel, $hash,$orgKat,$orgName);
        sendMail($email,$firstname,$lastname, $orgName);
    }
    
   
}
?>