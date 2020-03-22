<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if(isset($_POST['submitted'])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $usernameSmtp = 'AKIARQLZ7MA7QJLDSQGW';
    $passwordSmtp = 'BCl+IT/NdHN7HJ23RolPTcaB/8hOosQ7wHZE6aFRJM3+';

    //$configurationSet = 'ConfigSet';
    $host = 'email-smtp.us-east-1.amazonaws.com';
    $port = 587;

    $sender     = "noreply@remedymatch.dev";
    $senderName = "RemedyMatch";

    $subject    = "Ihre Nachricht an das Team von RemedyMatch:" .$_POST["subject"];
    $recipient = 'remedymatch2020@gmx.de';

    $bodyHtml = '<html>
  <body>
  <h1>Nachricht an das Team von RemedyMatch</h1>
   
  <p>Folgende Frage wurde über das Kontaktformular gestellt:</p>
  
  '.$message.'
  
  <p> Die Kontaktdaten sind: 
  </br> Name: '.$name.' </br>
  EMail: '.$email.'</p>
  <p>Diese E-Mail wurde automatisch erstellt, bitte antworten Sie nicht auf diese Email.</p>
   
  </body>
  </html>';


    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->setFrom($sender, $senderName);

        $mail->Username   = $usernameSmtp;
        $mail->Password   = $passwordSmtp;
        $mail->Host       = $host;
        $mail->Port       = $port;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = 'tls';
        $mail->CharSet    = 'utf-8';
        if(!empty($configurationSet))
            $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

        $mail->addAddress($recipient);

        $mail->isHTML(true);
        $mail->Subject    = $subject;
        $mail->Body       = $bodyHtml;
        $mail->Send();

        echo '<script type="text/javascript">';
        echo 'alert("Email erfolgreich versendet!")';
        echo '</script>';

    } catch (\phpmailerException $e) {

        echo '<script type="text/javascript">';
        echo 'alert("Leider ist ein Fehler beim Versand aufgetreten")';
        echo '</script>';

    } catch (\Exception $e) {

        echo '<script type="text/javascript">';
        echo 'alert("Leider ist ein Fehler beim Versand aufgetreten")';
        echo '</script>';

    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link
      href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900"
      rel="stylesheet"
    />

    <title>Hilft beim Helfen- RemedyMatch</title>
<!--
Reflux Template
https://templatemo.com/tm-531-reflux
-->
    <!-- Bootstrap core CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css" />
    <link rel="stylesheet" href="assets/css/templatemo-style.css" />
    <link rel="stylesheet" href="assets/css/owl.css" />
    <link rel="stylesheet" href="assets/css/lightbox.css" />
    
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="icon" type="image/png" href="favicon.png" sizes="32x32">
    <link rel="icon" type="image/png" href="favicon.png" sizes="96x96">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="mstile-144x144.png">
    
    
    <script type="text/javascript">
		var time =0;
		var timer;
		function setTime(){
			time = time +1;
			
		}
		function eingaben_ueberpruefen(){
			 clearInterval(timer);
			 if (time < 10) { // Botschutz
			  
			  document.contact.name.focus();
			  return false;
			 }
			 else
			 return true;
		}
		function startTimer(){
			timer =setInterval(setTime,1000);
		}
		
		</script>
    
    
    
  </head>

  <body>
    <div id="page-wraper">
      <!-- Sidebar Menu -->
      <div class="responsive-nav">
        <i class="fa fa-bars" id="menu-toggle"></i>
        <div id="menu" class="menu">
          <i class="fa fa-times" id="menu-close"></i>
          <div class="container">
            <div class="image">
              <a href="#"><img src="assets/images/logo.png" alt="" /></a>
            </div>
            <div class="author-content">
              <h4>RemedyMatch</h4>
              <span>Hilft beim Helfen</span>
            </div>
            <nav class="main-nav" role="navigation">
              <ul class="main-menu">
                <li><a href="#section2">Helfen/ Um Hilfe bitten</a></li>
                <li><a href="#section1">Über RemedyMatch</a></li>
                <li><a href="#section6">Mithelfen</a></li>
                <li><a href="#section3">Informationen</a></li>
                <li><a href="#section5">Aktuelle Fallzahlen</a></li>
                <li><a href="#section4">Kontakt</a></li>
                <li><a href="#section7">Über uns</a></li>
              </ul>
            </nav>
            <div class="social-network">
              <ul class="soial-icons">
                <li>
                  <a href="https://www.facebook.com/remedy.match.16" target="_blank"><i class="fa fa-facebook"></i
                  ></a>
                </li>
                <li>
                  <a href="https://twitter.com/RemedyMatch" target="_blank"><i class="fa fa-twitter"></i></a>
                </li>
                <li>
                  <a href="https://www.instagram.com/remedymatch/" target="_blank"><i class="fa fa-instagram"></i></a>
                </li>
                <li>
                  <a href="https://github.com/remedyMatch" target="_blank"><i class="fa fa-github"></i></a>
                </li>
              </ul>
            </div>
            <div class="copyright-text">
              <p>Copyright 2020 RemedyMatch</p>
            </div>
          </div>
        </div>
      </div>
      <section class="section my-services" data-section="section2">
        <div class="container">
          <div class="section-heading">
            <h2>Um Hilfe bitten</h2>
            <div class="line-dec"></div>
            <span
              >Suchen Sie hier aus, ob Sie Material bieten können oder ob Sie noch Material suchen.</span
            >
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="service-item">
                <div class="first-service-icon service-icon"></div>
                <h4>Hilfe anbieten</h4>
                <p>
                  Möchtest du Atemschutzmasken, Handschuhe oder sonstige Schutzartikel spenden, dann trage sie diese bitte hier ein:

                  <div class="white-button-service">
                    <a href="https://remedymatch.io/app/">Spende anbieten</a>
                  </div>
                </p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="service-item">
                <div class="first-service-icon service-icon"></div>
                <h4>Bedarf melden</h4>
                <p>
                  Benötigst du medizinische Schutzartikel, dann klicke hier:
                  <div class="white-button-service">
                    <a href="https://remedymatch.io/app/">Bedarf melden</a>
                  </div>
                </p>
              </div>
            </div>
           
            
          </div>
        </div>
      </section>
      <section class="section about-us" data-section="section1">
        <div class="container">
          <div class="section-heading">
            <h2>Über RemedyMatch</h2>
            <div class="line-dec"></div>
            <span>
              RemedyMatch ist eine deutschlandweite Logistikplattform, die Bestand und Bedarf von medizinischen Schutzartikeln zusammenbringt! 
              RemedyMatch erfasst zu spendende Schutzartikel, wie Handschuhe, Masken, Kittel oder Desinfektionsmittel, und sorgt dafür, dass diese dort ankommen, 
              wo sie gebraucht werden. So werden Lieferengpässe während der COVID-19 Pandemie überbrückt und der persönliche Schutz der Helferinnen und Helfer gewährleistet.


             
            </span>
            
          </div>
          <div class="left-image-post">
            <div class="row">
              <div class="col-md-6">
                <div class="left-image">
                  <img src="assets/images/Werbung1.png" alt="" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="right-text">
                  <h4>Für wen?</h4>
                  <p>
                      Alle, die medizinisch tätig sind oder besondere Hygienevorschriften einhalten müssen und aktuell einen Mangel erleben, dürfen einen Bedarf einstellen.
                      Das können Kliniken, niedergelassene Ärztinnen und Ärzte, Pflegekräfte, Mitarbeiter:innen im Einzelhandel oder pflegende Angehörige sein.
                  </p>
                  <h4>Wer spendet?</h4>
                  <p>
                      Es sind alle aufgerufen, Schutzartikel kostenfrei zur Verfügung zu stellen, die diese aktuell nicht benötigen. Das können Fitnessstudios, Restaurants, Hotels, 
                      Lackierer, Betriebe, Werkstätte oder Privathaushalte sein. Jeder Handschuh hilft!
                      </br>
                      Außerdem sucht RemedyMatch freiwillige Einzelpersonen oder Unternehmen, die beim Transport von Spenden unterstützen.

                  </p>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </section>

       <section class="section fall" data-section="section6">
        <div class="container">
            <div class="section-heading">
              <h2>Mithelfen</h2>
              <div class="line-dec"></div>
              <span>
                 Jede/r ist herzlich aufgerufen mitzuhelfen.
              </span>
            </div>
        </div>
        <div class="left-image-post">
            <div class="row">
              <div class="col-md-6">
                <div class="left-image">
                  <img src="assets/images/share.png" alt="" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="right-text">
                  <ol>  
                    <li>Schutzartikel spenden </li>
                    <li>Freiwillig beim Transport helfen </li>
                    <li>Weitersagen: Wenn ihr wisst, in wessen Keller oder Lager noch Schätze schlummern, erzählt ihnen von RemedyMatch </li>
                    <li>Auf Sozialen Medien teilen</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
      </section>

      <section class="section my-work" data-section="section3">
        <div class="container">
          <div class="section-heading">
            <h2>Informationen</h2>
            <div class="line-dec"></div>
            
          </div>
          <div class="row">
            <div class="isotope-wrapper">
                <div class="isotope-box">
                <div class="isotope-item" data-type="people">
                  <figure class="snip1321">
                    <img
                      src="assets/images/virus.png"
                      alt="sq-sample26"
                    />
                    <figcaption>
                      <a
                        href="assets/images/virus.png"
                        data-lightbox="image-1"
                        data-title="Der SARS-CoV-2 Virus"
                        ><i class="fa fa-search"></i
                      ></a>
                      <h4>Der SARS-CoV-2 Virus</h4>
                      <span>Der Der SARS-CoV-2 Virus kann die gefährliche Krankheit Covid19 auslösen.</span>
                    </figcaption>
                  </figure>
                </div>

               
                <div class="isotope-item" data-type="nature">
                  <figure class="snip1321">
                    <img
                      src="assets/images/mask.jpg"
                      alt="sq-sample26"
                    />
                    <figcaption>
                      <a
                        href="assets/images/mask.jpg"
                        data-lightbox="image-1"
                        data-title="Atemschutzmasken der Schutzklasse FFP-2"
                        ><i class="fa fa-search"></i
                      ></a>
                      <h4>Atemschutzmasken der Schutzklasse FFP-2</h4>
                      <span>Diese Schutzmaske, soll laut dem RKI, die Pflegedienstmitarbeiter vor einer Infektion mit dem SARS-CoV-2 schützen.</span>
                    </figcaption>
                  </figure>
                </div>
                 <div class="isotope-item" data-type="animals">
                  <figure class="snip1321">
                    <img
                      src="assets/images/fallzahlen.jpg"
                      alt="sq-sample26"
                    />
                    <figcaption>
                      <a
                        href="assets/images/fallzahlen.jpg"
                        data-lightbox="image-1"
                        data-title="Fallzahlen in Deutschland"
                        ><i class="fa fa-search"></i
                      ></a>
                      <h4>Fallzahlen in Deutschland</h4>
                      <span>Klicken Sie hier um einen Überblick über die aktuellen Fallzahlen in Deutschland zu erhalten.</span>
                    </figcaption>
                  </figure>
                </div>
                

                <div class="isotope-item" data-type="people">
                  <figure class="snip1321">
                    <img
                      src="assets/images/einmalhandschuhe.jpg"
                      alt="sq-sample26"
                    />
                    <figcaption>
                      <a
                        href="assets/images/einmalhandschuhe.jpg"
                        data-lightbox="image-1"
                        data-title="Caption"
                        ><i class="fa fa-search"></i
                      ></a>
                      <h4>Einmalhandschuhe</h4>
                      <span>Diese Handschuhe sollen das Personal vor einer Corona-Infektion schützen, werden aber langsam knapp.</span>
                    </figcaption>
                  </figure>
                </div>

              </div>
            </div>
          </div>
        </div>
        
      </section>
      <section class="section fall" data-section="section5">
        <div class="container">
            <div class="section-heading">
              <h2>Die aktuellen Fallzahlen</h2>
              <div class="line-dec"></div>
              <span>
                Die aktuellen Fallzahlen bereit gestellt durch Johns Hopkins Center for Systems Science and Engineering.
              </span>
            </div>
          </div>
            <div style="padding-top: 0%; position: relative; width:100%; height:600px;">
              <iframe src="https://www.arcgis.com/apps/opsdashboard/index.html#/bda7594740fd40299423467b48e9ecf6" height="100%" width="100%"></iframe>
            </div>

      </section>
      <section class="section contact-us" data-section="section4">
        <div class="container">
          <div class="section-heading">
            <h2>Kontaktieren Sie uns:</h2>
            <div class="line-dec"></div>
            <span
              >Bei Fragen, Feedback, Vorschlägen oder wenn ihr mithelfen wollt bei der Weiterentwicklung der Plattform, meldet euch gern!</span
            >
          </div>
          <div class="row">
            <div class="right-content">
              <div class="container">
                <form id="contact" action="index.php" method="post" onSubmit="return eingaben_ueberpruefen();" name="contact">
                  <div class="row">
                    <div class="col-md-6">
                      <fieldset>
                        <input
                          name="name"
                          type="text"
                          class="form-control"
                          id="name"
                          placeholder="Ihr Name..."
                          required="" onFocus="startTimer();"
                        />
                      </fieldset>
                    </div>
                    <div class="col-md-6">
                      <fieldset>
                        <input
                          name="email"
                          type="text"
                          class="form-control"
                          id="email"
                          placeholder="Ihre Email..."
                          required=""
                        />
                      </fieldset>
                    </div>
                    <div class="col-md-12">
                      <fieldset>
                        <input
                          name="subject"
                          type="text"
                          class="form-control"
                          id="subject"
                          placeholder="Betreff ihrer Anfrage..."
                          required=""
                        />
                      </fieldset>
                    </div>
                    <div class="col-md-12">
                      <fieldset>
                        <textarea
                          name="message"
                          rows="6"
                          class="form-control"
                          id="message"
                          placeholder="Ihre Nachricht..."
                          required=""
                        ></textarea>
                      </fieldset>
                    </div>
                    <div class="col-md-12">
                      <fieldset>
                        <button type="submit" id="form-submit" class="button" name="submitted">
                          Nachricht senden
                        </button>
                      </fieldset>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
       <section class="section fall" data-section="section7">
        <div class="container">
            <div class="section-heading">
              <h2>Über uns</h2>
              <div class="line-dec"></div>
              <span>
                RemedyMatch wurde entwickelt von einem Team aus etwa 30 Leuten während des Hackathons #WirvsVirus vom 21.-23. März 2020.
              </span>
            </div>
          </div>
      </section>
      
      <section class="section dataprotection" data-section="dataprotection">
        <div class="container">
            <div class="section-heading">
              <h2>Datenscchutzerklärung</h2>
              <div class="line-dec"></div>
              <span>
                  <p>Personenbezogene Daten (nachfolgend zumeist nur „Daten“ genannt) werden von uns nur im Rahmen der Erforderlichkeit sowie zum Zwecke der Bereitstellung eines funktionsf&auml;higen und nutzerfreundlichen Internetauftritts, inklusive seiner Inhalte und der dort angebotenen Leistungen, verarbeitet.</p>
                  <p>Gem&auml;&szlig; Art. 4 Ziffer 1. der Verordnung (EU) 2016/679, also der Datenschutz-Grundverordnung (nachfolgend nur „DSGVO“ genannt), gilt als „Verarbeitung“ jeder mit oder ohne Hilfe automatisierter Verfahren ausgef&uuml;hrter Vorgang oder jede solche Vorgangsreihe im Zusammenhang mit personenbezogenen Daten, wie das Erheben, das Erfa&szlig;en, die Organisation, das Ordnen, die Speicherung, die Anpa&szlig;ung oder Ver&auml;nderung, das Auslesen, das Abfragen, die Verwendung, die Offenlegung durch &uuml;bermittlung, Verbreitung oder eine andere Form der Bereitstellung, den Abgleich oder die Verkn&uuml;pfung, die Einschr&auml;nkung, das L&ouml;schen oder die Vernichtung.</p>
                  <p>Mit der nachfolgenden Datenschutzerkl&auml;rung informieren wir Sie insbesondere &uuml;ber Art, Umfang, Zweck, Dauer und Rechtsgrundlage der Verarbeitung personenbezogener Daten, soweit wir entweder allein oder gemeinsam mit anderen &uuml;ber die Zwecke und Mittel der Verarbeitung entscheiden. Zudem informieren wir Sie nachfolgend &uuml;ber die von uns zu Optimierungszwecken sowie zur Steigerung der Nutzungsqualit&auml;t eingesetzten Fremdkomponenten, soweit hierdurch Dritte Daten in wiederum eigener Verantwortung verarbeiten.</p>
                  <p>Unsere Datenschutzerkl&auml;rung ist wie folgt gegliedert:</p>
                  <p>I. Informationen &uuml;ber uns als Verantwortliche<br>II. Rechte der Nutzer und Betroffenen<br>III. Informationen zur Datenverarbeitung</p>
                  <h3>I. Informationen &uuml;ber uns als Verantwortliche</h3>
                  <p>Verantwortlicher Anbieter dieses Internetauftritts im datenschutzrechtlichen Sinne ist:</p>
                  <p><span>RemedyMatch</span><p></p>
                  <p>Die verantwortliche Stelle entscheidet allein oder gemeinsam mit anderen &uuml;ber die Zwecke und Mittel der Verarbeitung von personenbezogenen Daten (z.B. Namen, Kontaktdaten o. &auml;.).</p>


                  <h3>II. Rechte der Nutzer und Betroffenen</h3>
                  <p>Mit Blick auf die nachfolgend noch n&auml;her beschriebene Datenverarbeitung haben die Nutzer und Betroffenen das Recht</p>
                  <ul>
                  <li>auf Best&auml;tigung, ob sie betreffende Daten verarbeitet werden, auf Auskunft &uuml;ber die verarbeiteten Daten, auf weitere Informationen &uuml;ber die Datenverarbeitung sowie auf Kopien der Daten (vgl. auch Art. 15 DSGVO);</li>
                  <li>auf Berichtigung oder Vervollst&auml;ndigung unrichtiger bzw. unvollst&auml;ndiger Daten (vgl. auch Art. 16 DSGVO);</li>
                  <li>auf unverz&uuml;gliche L&ouml;schung der sie betreffenden Daten (vgl. auch Art. 17 DSGVO), oder, alternativ, soweit eine weitere Verarbeitung gem&auml;&szlig; Art. 17 Abs. 3 DSGVO erforderlich ist, auf Einschr&auml;nkung der Verarbeitung nach Ma&szlig;gabe von Art. 18 DSGVO;</li>
                  <li>auf Erhalt der sie betreffenden und von ihnen bereitgestellten Daten und auf &uuml;bermittlung dieser Daten an andere Anbieter/Verantwortliche (vgl. auch Art. 20 DSGVO);</li>
                  <li>auf Beschwerde gegen&uuml;ber der Aufsichtsbeh&ouml;rde, sofern sie der Ansicht sind, da&szlig; die sie betreffenden Daten durch den Anbieter unter Versto&szlig; gegen datenschutzrechtliche Bestimmungen verarbeitet werden (vgl. auch Art. 77 DSGVO).</li>
                  </ul>
                  <p>Dar&uuml;ber hinaus ist der Anbieter dazu verpflichtet, alle Empf&auml;nger, denen gegen&uuml;ber Daten durch den Anbieter offengelegt worden sind, &uuml;ber jedwede Berichtigung oder L&ouml;schung von Daten oder die Einschr&auml;nkung der Verarbeitung, die aufgrund der Artikel 16, 17 Abs. 1, 18 DSGVO erfolgt, zu unterrichten. Diese Verpflichtung besteht jedoch nicht, soweit diese Mitteilung unm&ouml;glich oder mit einem unverh&auml;ltnism&auml;&szlig;igen Aufwand verbunden ist. Unbeschadet de&szlig;en hat der Nutzer ein Recht auf Auskunft &uuml;ber diese Empf&auml;nger.</p>
                  <p><strong>Ebenfalls haben die Nutzer und Betroffenen nach Art. 21 DSGVO das Recht auf Widerspruch gegen die k&uuml;nftige Verarbeitung der sie betreffenden Daten, sofern die Daten durch den Anbieter nach Ma&szlig;gabe von Art. 6 Abs. 1 lit. f) DSGVO verarbeitet werden. Insbesondere ist ein Widerspruch gegen die Datenverarbeitung zum Zwecke der Direktwerbung statthaft.</strong></p>
                  <h3>III. Informationen zur Datenverarbeitung</h3>
                  <p>Ihre bei Nutzung unseres Internetauftritts verarbeiteten Daten werden gel&ouml;scht oder gesperrt, sobald der Zweck der Speicherung entf&auml;llt, der L&ouml;schung der Daten keine gesetzlichen Aufbewahrungspflichten entgegenstehen und nachfolgend keine anderslautenden Angaben zu einzelnen Verarbeitungsverfahren gemacht werden.</p>

                  <h4>Kontaktanfragen / Kontaktm&ouml;glichkeit</h4>
                  <p>Sofern Sie per Kontaktformular oder E-Mail mit uns in Kontakt treten, werden die dabei von Ihnen angegebenen Daten zur Bearbeitung Ihrer Anfrage genutzt. Die Angabe der Daten ist zur Bearbeitung und Beantwortung Ihre Anfrage erforderlich - ohne deren Bereitstellung k&ouml;nnen wir Ihre Anfrage nicht oder allenfalls eingeschr&auml;nkt beantworten.</p>
                  <p>Rechtsgrundlage f&uuml;r diese Verarbeitung ist Art. 6 Abs. 1 lit. b) DSGVO.</p>
                  <p>Ihre Daten werden gel&ouml;scht, sofern Ihre Anfrage abschlie&szlig;end beantwortet worden ist und der L&ouml;schung keine gesetzlichen Aufbewahrungspflichten entgegenstehen, wie bspw. bei einer sich etwaig anschlie&szlig;enden Vertragsabwicklung.</p>

                  <h4>Nutzerbeitr&auml;ge, Kommentare und Bewertungen</h4>
                  <p>Wir bieten Ihnen an, auf unseren Internetseiten Fragen, Antworten, Meinungen oder Bewertungen, nachfolgend nur „Beitr&auml;ge genannt, zu ver&ouml;ffentlichen. Sofern Sie dieses Angebot in Anspruch nehmen, verarbeiten und ver&ouml;ffentlichen wir Ihren Beitrag, Datum und Uhrzeit der Einreichung sowie das von Ihnen ggf. genutzte Pseudonym.</p>
                  <p>Rechtsgrundlage hierbei ist Art. 6 Abs. 1 lit. a) DSGVO. Die Einwilligung k&ouml;nnen Sie gem&auml;&szlig; Art. 7 Abs. 3 DSGVO jederzeit mit Wirkung f&uuml;r die Zukunft widerrufen. Hierzu m&uuml;&szlig;en Sie uns lediglich &uuml;ber Ihren Widerruf in Kenntnis setzen.</p>
                  <p>Dar&uuml;ber hinaus verarbeiten wir auch Ihre IP- und E-Mail-Adre&szlig;e. Die IP-Adre&szlig;e wird verarbeitet, weil wir ein berechtigtes Intere&szlig;e daran haben, weitere Schritte einzuleiten oder zu unterst&uuml;tzen, sofern Ihr Beitrag in Rechte Dritter eingreift und/oder er sonst wie rechtswidrig erfolgt.</p>
                  <p>Rechtsgrundlage ist in diesem Fall Art. 6 Abs. 1 lit. f) DSGVO. Unser berechtigtes Intere&szlig;e liegt in der ggf. notwendigen Rechtsverteidigung.</p>

                  <h4>Facebook</h4>
                  <p>Zur Bewerbung unserer Produkte und Leistungen sowie zur Kommunikation mit Intere&szlig;enten oder Kunden betreiben wir eine Firmenpr&auml;senz auf der Plattform Facebook.</p>
                  <p>Auf dieser Social-Media-Plattform sind wir gemeinsam mit der Facebook Ireland Ltd., 4 Grand Canal Square, Grand Canal Harbour, Dublin 2 Ireland, verantwortlich.</p>
                  <p>Der Datenschutzbeauftragte von Facebook kann &uuml;ber ein Kontaktformular erreicht werden:</p>
                  <p><a href="https://www.facebook.com/help/contact/540977946302970" target="_blank" rel="noopener">https://www.facebook.com/help/contact/540977946302970</a></p>
                  <p>Die gemeinsame Verantwortlichkeit haben wir in einer Vereinbarung bez&uuml;glich der jeweiligen Verpflichtungen im Sinne der DSGVO geregelt. Diese Vereinbarung, aus der sich die gegenseitigen Verpflichtungen ergeben, ist unter dem folgenden Link abrufbar:</p>
                  <p><a href="https://www.facebook.com/legal/terms/page_controller_addendum" target="_blank" rel="noopener">https://www.facebook.com/legal/terms/page_controller_addendum</a></p>
                  <p>Rechtsgrundlage f&uuml;r die dadurch erfolgende und nachfolgend wiedergegebene Verarbeitung von personenbezogenen Daten ist Art. 6 Abs. 1 lit. f DSGVO. Unser berechtigtes Intere&szlig;e besteht an der Analyse, der Kommunikation sowie dem Absatz und der Bewerbung unserer Produkte und Leistungen.</p>
                  <p>Rechtsgrundlage kann auch eine Einwilligung des Nutzers gem&auml;&szlig; Art. 6 Abs. 1 lit. a DSGVO gegen&uuml;ber dem Plattformbetreiber sein. Die Einwilligung hierzu kann der Nutzer nach Art. 7 Abs. 3 DSGVO jederzeit durch eine Mitteilung an den Plattformbetreiber f&uuml;r die Zukunft widerrufen.</p>
                  <p>Bei dem Aufruf unseres Onlineauftritts auf der Plattform Facebook werden von der Facebook Ireland Ltd. als Betreiberin der Plattform in der EU Daten des Nutzers (z.B. pers&ouml;nliche Informationen, IP-Adre&szlig;e etc.) verarbeitet.</p>
                  <p>Diese Daten des Nutzers dienen zu statistischen Informationen &uuml;ber die Inanspruchnahme unserer Firmenpr&auml;senz auf Facebook. Die Facebook Ireland Ltd. nutzt diese Daten zu Marktforschungs- und Werbezwecken sowie zur Erstellung von Profilen der Nutzer. Anhand dieser Profile ist es der Facebook Ireland Ltd. beispielsweise m&ouml;glich, die Nutzer innerhalb und au&szlig;erhalb von Facebook intere&szlig;enbezogen zu bewerben. Ist der Nutzer zum Zeitpunkt des Aufrufes in seinem Account auf Facebook eingeloggt, kann die Facebook Ireland Ltd. zudem die Daten mit dem jeweiligen Nutzerkonto verkn&uuml;pfen.</p>
                  <p>Im Falle einer Kontaktaufnahme des Nutzers &uuml;ber Facebook werden die bei dieser Gelegenheit eingegebenen personenbezogenen Daten des Nutzers zur Bearbeitung der Anfrage genutzt. Die Daten des Nutzers werden bei uns gel&ouml;scht, sofern die Anfrage des Nutzers abschlie&szlig;end beantwortet wurde und keine gesetzlichen Aufbewahrungspflichten, wie z.B. bei einer anschlie&szlig;enden Vertragsabwicklung, entgegenstehen.</p>
                  <p>Zur Verarbeitung der Daten werden von der Facebook Ireland Ltd. ggf. auch Cookies gesetzt.</p>
                  <p>Sollte der Nutzer mit dieser Verarbeitung nicht einverstanden sein, so besteht die M&ouml;glichkeit, die Installation der Cookies durch eine entsprechende Einstellung des Browsers zu verhindern. Bereits gespeicherte Cookies k&ouml;nnen ebenfalls jederzeit gel&ouml;scht werden. Die Einstellungen hierzu sind vom jeweiligen Browser abh&auml;ngig. Bei Flash-Cookies l&auml;&szlig;t sich die Verarbeitung nicht &uuml;ber die Einstellungen des Browsers unterbinden, sondern durch die entsprechende Einstellung des Flash-Players. Sollte der Nutzer die Installation der Cookies verhindern oder einschr&auml;nken, kann dies dazu f&uuml;hren, da&szlig; nicht s&auml;mtliche Funktionen von Facebook vollumf&auml;nglich nutzbar sind.</p>
                  <p>&nbsp;</p>
                  <p>N&auml;heres zu den Verarbeitungst&auml;tigkeiten, deren Unterbindung und zur L&ouml;schung der von Facebook verarbeiteten Daten finden sich in der Datenrichtlinie von Facebook:</p>
                  <p><a href="https://www.facebook.com/privacy/explanation" target="_blank" rel="noopener">https://www.facebook.com/privacy/explanation</a></p>
                  <p>Es ist nicht ausgeschlo&szlig;en, da&szlig; die Verarbeitung durch die Facebook Ireland Ltd. auch &uuml;ber die Facebook Inc., 1601 Willow Road, Menlo Park, California 94025 in den USA erfolgt.</p>
                  <p>Die Facebook Inc. hat sich dem „EU-US Privacy Shield“ unterworfen und erkl&auml;rt dadurch die Einhaltung der Datenschutzvorgaben der EU bei der Verarbeitung der Daten in den USA.</p>
                  <p><a href="https://www.privacyshield.gov/participant?id=a2zt0000000GnywAAC&amp;status=Active" target="_blank" rel="noopener">https://www.privacyshield.gov/participant?id=a2zt0000000GnywAAC&amp;status=Active</a> </p>

                  <h4>Instagram</h4>
                  <p>Zur Bewerbung unserer Produkte und Leistungen sowie zur Kommunikation mit Intere&szlig;enten oder Kunden betreiben wir eine Firmenpr&auml;senz auf der Plattform Instagram.</p>
                  <p>Auf dieser Social-Media-Plattform sind wir gemeinsam mit der Facebook Ireland Ltd., 4 Grand Canal Square, Grand Canal Harbour, Dublin 2 Ireland, verantwortlich.</p>
                  <p>Der Datenschutzbeauftragte von Instagram kann &uuml;ber ein Kontaktformular erreicht werden:</p>
                  <p><a href="https://www.facebook.com/help/contact/540977946302970" target="_blank" rel="noopener">https://www.facebook.com/help/contact/540977946302970</a></p>
                  <p>Die gemeinsame Verantwortlichkeit haben wir in einer Vereinbarung bez&uuml;glich der jeweiligen Verpflichtungen im Sinne der DSGVO geregelt. Diese Vereinbarung, aus der sich die gegenseitigen Verpflichtungen ergeben, ist unter dem folgenden Link abrufbar:</p>
                  <p><a href="https://www.facebook.com/legal/terms/page_controller_addendum" target="_blank" rel="noopener">https://www.facebook.com/legal/terms/page_controller_addendum</a></p>
                  <p>Rechtsgrundlage f&uuml;r die dadurch erfolgende und nachfolgend wiedergegebene Verarbeitung von personenbezogenen Daten ist Art. 6 Abs. 1 lit. f DSGVO. Unser berechtigtes Intere&szlig;e besteht an der Analyse, der Kommunikation sowie dem Absatz und der Bewerbung unserer Produkte und Leistungen.</p>
                  <p>Rechtsgrundlage kann auch eine Einwilligung des Nutzers gem&auml;&szlig; Art. 6 Abs. 1 lit. a DSGVO gegen&uuml;ber dem Plattformbetreiber sein. Die Einwilligung hierzu kann der Nutzer nach Art. 7 Abs. 3 DSGVO jederzeit durch eine Mitteilung an den Plattformbetreiber f&uuml;r die Zukunft widerrufen.</p>
                  <p>Bei dem Aufruf unseres Onlineauftritts auf der Plattform Instagram werden von der Facebook Ireland Ltd. als Betreiberin der Plattform in der EU Daten des Nutzers (z.B. pers&ouml;nliche Informationen, IP-Adre&szlig;e etc.) verarbeitet.</p>
                  <p>Diese Daten des Nutzers dienen zu statistischen Informationen &uuml;ber die Inanspruchnahme unserer Firmenpr&auml;senz auf Instagram. Die Facebook Ireland Ltd. nutzt diese Daten zu Marktforschungs- und Werbezwecken sowie zur Erstellung von Profilen der Nutzer. Anhand dieser Profile ist es der Facebook Ireland Ltd. beispielsweise m&ouml;glich, die Nutzer innerhalb und au&szlig;erhalb von Instagram intere&szlig;enbezogen zu bewerben. Ist der Nutzer zum Zeitpunkt des Aufrufes in seinem Account auf Instagram eingeloggt, kann die Facebook Ireland Ltd. zudem die Daten mit dem jeweiligen Nutzerkonto verkn&uuml;pfen.</p>
                  <p>Im Falle einer Kontaktaufnahme des Nutzers &uuml;ber Instagram werden die bei dieser Gelegenheit eingegebenen personenbezogenen Daten des Nutzers zur Bearbeitung der Anfrage genutzt. Die Daten des Nutzers werden bei uns gel&ouml;scht, sofern die Anfrage des Nutzers abschlie&szlig;end beantwortet wurde und keine gesetzlichen Aufbewahrungspflichten, wie z.B. bei einer anschlie&szlig;enden Vertragsabwicklung, entgegenstehen.</p>
                  <p>Zur Verarbeitung der Daten werden von der Facebook Ireland Ltd. ggf. auch Cookies gesetzt.</p>
                  <p>Sollte der Nutzer mit dieser Verarbeitung nicht einverstanden sein, so besteht die M&ouml;glichkeit, die Installation der Cookies durch eine entsprechende Einstellung des Browsers zu verhindern. Bereits gespeicherte Cookies k&ouml;nnen ebenfalls jederzeit gel&ouml;scht werden. Die Einstellungen hierzu sind vom jeweiligen Browser abh&auml;ngig. Bei Flash-Cookies l&auml;&szlig;t sich die Verarbeitung nicht &uuml;ber die Einstellungen des Browsers unterbinden, sondern durch die entsprechende Einstellung des Flash-Players. Sollte der Nutzer die Installation der Cookies verhindern oder einschr&auml;nken, kann dies dazu f&uuml;hren, da&szlig; nicht s&auml;mtliche Funktionen von Facebook vollumf&auml;nglich nutzbar sind.</p>
                  <p>N&auml;heres zu den Verarbeitungst&auml;tigkeiten, deren Unterbindung und zur L&ouml;schung der von Instagram verarbeiteten Daten finden sich in der Datenrichtlinie von Instagram:</p>
                  <p><a href="https://help.instagram.com/519522125107875" target="_blank" rel="noopener">https://help.instagram.com/519522125107875</a></p>
                  <p>Es ist nicht ausgeschlo&szlig;en, da&szlig; die Verarbeitung durch die Facebook Ireland Ltd. auch &uuml;ber die Facebook Inc., 1601 Willow Road, Menlo Park, California 94025 in den USA erfolgt.</p>
                  <p>Die Facebook Inc. hat sich dem „EU-US Privacy Shield“ unterworfen und erkl&auml;rt dadurch die Einhaltung der Datenschutzvorgaben der EU bei der Verarbeitung der Daten in den USA.</p>
                  <p><a href="https://www.privacyshield.gov/participant?id=a2zt0000000GnywAAC&amp;status=Active" target="_blank" rel="noopener">https://www.privacyshield.gov/participant?id=a2zt0000000GnywAAC&amp;status=Active</a></p>

                  <h4>„Facebook“-Social-Plug-in</h4>
                  <p>In unserem Internetauftritt setzen wir das Plug-in des Social-Networks Facebook ein. Bei Facebook handelt es sich um einen Internetservice der facebook Inc., 1601 S. California Ave, Palo Alto, CA 94304, USA. In der EU wird dieser Service wiederum von der Facebook Ireland Limited, 4 Grand Canal Square, Dublin 2, Irland, betrieben, nachfolgend beide nur „Facebook“ genannt.</p>
                  <p>Durch die Zertifizierung nach dem EU-US-Datenschutzschild („EU-US Privacy Shield“)</p>
                  <p><a target="_blank" rel="noopener" href="https://www.privacyshield.gov/participant?id=a2zt0000000GnywAAC&amp;status=Active">https://www.privacyshield.gov/participant?id=a2zt0000000GnywAAC&amp;status=Active</a></p>
                  <p>garantiert Facebook, da&szlig; die Datenschutzvorgaben der EU auch bei der Verarbeitung von Daten in den USA eingehalten werden.</p>
                  <p>Rechtsgrundlage ist Art. 6 Abs. 1 lit. f) DSGVO. Unser berechtigtes Intere&szlig;e liegt in der Qualit&auml;tsverbe&szlig;erung unseres Internetauftritts.</p>
                  <p>Weitergehende Informationen &uuml;ber die m&ouml;glichen Plug-ins sowie &uuml;ber deren jeweilige Funktionen h&auml;lt Facebook unter</p>
                  <p><a target="_blank" rel="noopener" href="https://developers.facebook.com/docs/plugins/">https://developers.facebook.com/docs/plugins/</a></p>
                  <p>f&uuml;r Sie bereit.</p>
                  <p>Sofern das Plug-in auf einer der von Ihnen besuchten Seiten unseres Internetauftritts hinterlegt ist, l&auml;dt Ihr Internet-Browser eine Darstellung des Plug-ins von den Servern von Facebook in den USA herunter. Aus technischen Gr&uuml;nden ist es dabei notwendig, da&szlig; Facebook Ihre IP-Adre&szlig;e verarbeitet. Daneben werden aber auch Datum und Uhrzeit des Besuchs unserer Internetseiten erfa&szlig;t.</p>
                  <p>Sollten Sie bei Facebook eingeloggt sein, w&auml;hrend Sie eine unserer mit dem Plug-in versehenen Internetseite besuchen, werden die durch das Plug-in gesammelten Informationen Ihres konkreten Besuchs von Facebook erkannt. Die so gesammelten Informationen weist Facebook wom&ouml;glich Ihrem dortigen pers&ouml;nlichen Nutzerkonto zu. Sofern Sie also bspw. den sog. „Gef&auml;llt mir“-Button von Facebook benutzen, werden diese Informationen in Ihrem Facebook-Nutzerkonto gespeichert und ggf. &uuml;ber die Plattform von Facebook ver&ouml;ffentlicht. Wenn Sie das verhindern m&ouml;chten, m&uuml;&szlig;en Sie sich entweder vor dem Besuch unseres Internetauftritts bei Facebook ausloggen oder durch den Einsatz eines Add-ons f&uuml;r Ihren Internetbrowser verhindern, da&szlig; das Laden des Facebook-Plug-in blockiert wird.</p>
                  <p>Weitergehende Informationen &uuml;ber die Erhebung und Nutzung von Daten sowie Ihre diesbez&uuml;glichen Rechte und Schutzm&ouml;glichkeiten h&auml;lt Facebook in den unter</p>
                  <p><a target="_blank" rel="noopener" href="https://www.facebook.com/policy.php">https://www.facebook.com/policy.php</a></p>
                  <p>abrufbaren Datenschutzhinweisen bereit.</p>
              </span>
            </div>
          </div>
      </section>
    </div>

    <!-- Scripts -->
    <!-- Bootstrap core JavaScript -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/isotope.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/lightbox.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
      //according to loftblog tut
      $(".main-menu li:first").addClass("active");

      var showSection = function showSection(section, isAnimate) {
        var direction = section.replace(/#/, ""),
          reqSection = $(".section").filter(
            '[data-section="' + direction + '"]'
          ),
          reqSectionPos = reqSection.offset().top - 0;

        if (isAnimate) {
          $("body, html").animate(
            {
              scrollTop: reqSectionPos
            },
            800
          );
        } else {
          $("body, html").scrollTop(reqSectionPos);
        }
      };

      var checkSection = function checkSection() {
        $(".section").each(function() {
          var $this = $(this),
            topEdge = $this.offset().top - 80,
            bottomEdge = topEdge + $this.height(),
            wScroll = $(window).scrollTop();
          if (topEdge < wScroll && bottomEdge > wScroll) {
            var currentId = $this.data("section"),
              reqLink = $("a").filter("[href*=\\#" + currentId + "]");
            reqLink
              .closest("li")
              .addClass("active")
              .siblings()
              .removeClass("active");
          }
        });
      };

      $(".main-menu").on("click", "a", function(e) {
        e.preventDefault();
        showSection($(this).attr("href"), true);
      });

      $(window).scroll(function() {
        checkSection();
      });
    </script>
  </body>
</html>
