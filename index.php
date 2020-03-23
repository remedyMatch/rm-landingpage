<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['submitted'])) {
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

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyHtml;
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

<?php include(__DIR__ . '/src/includes/header.html'); ?>
    <section class="section about-us" data-section="about-remedy" id="about-remedy">
        <div class="container">
            <div class="section-heading">
                <h2>Über RemedyMatch</h2>
                <p>REMEDYMATCH ist ein System zur Schaffung einer bundesweiten Bestands- und
                    Bedarfsübersicht von medizinischen Schutzartikeln für Institutionen im Kampf gegen COVID-19.</p>
                <div class="line-dec"></div>
                <div class="alert alert-light" role="alert">
                    <h4 style="color: #0b2e13">Portal im Aufbau!</h4>
                    Du möchtest Hilfe anbieten oder benötigst medizinische Schutzmaterialien? Dann trag dich <b>kostenfrei</b>
                    ein und wir benachrichtigen dich, sobald unser Portal genutzt werden kann.
                    <hr>
                    <div class="mb-0">
                        <?php if (isset($_GET['preregister']) && $_GET['preregister'] == 'success'): ?>
                            <h3>🙏 Ihre Vormerkung war erfolgreich! Vielen Dank! 🙏</h3>
                        <?php else: ?>
                            <form class="form" method="post" action="/forms.php">
                                <div class="form-row align-items-center">
                                    <div class="col">
                                        <label class="sr-only" for="inlineFormInputName2">Name/Firma</label>
                                        <input type="text" class="form-control mb-2 mr-sm-2"
                                               id="inlineFormInputGroupUsername2"
                                               placeholder="Name/Firma" name="name">
                                    </div>
                                    <div class="col">
                                        <label class="sr-only" for="inlineFormInputGroupUsername2">E-Mailadresse</label>
                                        <div class="input-group mb-2 mr-sm-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">@</div>
                                            </div>
                                            <input type="text" class="form-control" id="inlineFormInputGroupUsername2"
                                                   placeholder="E-Mail" name="email">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mb-2 btn-block" name="preregister">Vormerken
                                            lassen
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card">
                    <iframe width="100%" height="400" src="https://www.youtube.com/embed/QIhFcmSnDLQ" frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    <div class="card-body">
                        <p class="card-text" style="color: #000000; text-align: justify;">RemedyMatch ist eine
                            deutschlandweite Logistikplattform, die Bestand und Bedarf von medizinischen Schutzartikeln
                            zusammenbringt!
                            RemedyMatch erfasst zu spendende Schutzartikel, wie Handschuhe, Masken, Kittel oder
                            Desinfektionsmittel, und sorgt dafür, dass diese dort ankommen,
                            wo sie gebraucht werden. So werden Lieferengpässe während der COVID-19 Pandemie überbrückt
                            und der persönliche Schutz der Helferinnen und Helfer gewährleistet.</p>
                    </div>
                </div>
            </div>

            <div class="left-image-post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="left-image">
                            <img src="assets/images/Werbung1.png" alt=""/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="right-text">
                            <h4>Für wen?</h4>
                            <p>
                                Alle, die medizinisch tätig sind oder besondere Hygienevorschriften einhalten müssen und
                                aktuell einen Mangel erleben, dürfen einen Bedarf einstellen.
                                Das können Kliniken, niedergelassene Ärztinnen und Ärzte, Pflegekräfte,
                                Mitarbeiter:innen im Einzelhandel oder pflegende Angehörige sein.
                            </p>
                            <h4>Wer spendet?</h4>
                            <p>
                                Es sind alle aufgerufen, Schutzartikel kostenfrei zur Verfügung zu stellen, die diese
                                aktuell nicht benötigen. Das können Fitnessstudios, Restaurants, Hotels,
                                Lackierer, Betriebe, Werkstätte oder Privathaushalte sein. Jeder Handschuh hilft!
                                </br>
                                Außerdem sucht RemedyMatch freiwillige Einzelpersonen oder Unternehmen, die beim
                                Transport von Spenden unterstützen.
                            </p>
                            <h4>Bedarfserbringer</h4>
                            <p>Betriebe, Restaurants, Haushalte, Gesundheitseinrichtungen und
                                Privatpersonen
                                pflegen den Bestand lagernder Schutzartikel in die Plattform ein.</p>
                            <h4>Bedarfsträger</h4>
                            <p>Krankenhäuser, Ärzte, Pflegedienste, pflegende Privatpersonen und
                                weitere
                                Institutionen können über REMEDYMATCH innerhalb kürzester Zeit einen aktuellen Überblick
                                über
                                verfügbare medizinische Schutzausrüstung erhalten und den Kontakt zu dem/den
                                Bedarfserbringer/n
                                aufnehmen, welche Ressourcen zur Verfügung stellen können um die Lieferengpässe dieser
                                Artikel zu
                                überbrücken.</p>
                            <h4>Logistik</h4>
                            <p>
                                Ebenso bietet REMEDYMATCH die Möglichkeit Logistikpartner einzubinden und Angebote für
                                Transportunterstützung anzufordern. Hierzu können sich Logistikpartner registrieren um
                                gemeinnützige
                                Unterstützungsleistung anzubieten. Über Geomatching werden Bedarfsträger,
                                Bedarfserbringer und
                                Logistikpartner miteinander verknüpft und können sich über eine integrierte
                                Chat-Funktion
                                verständigen.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="section my-services" data-section="signup" id="signup">
        <div class="container">
            <div class="section-heading">
                <h2>Um Hilfe bitten</h2>
                <div class="line-dec"></div>
                <span>Suchen Sie hier aus, ob Sie Material bieten können oder ob Sie noch Material suchen.</br>
                    Testzugangsdaten: </br>Benutzer: test </br>Password: test
</span>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="service-item">
                        <div class="first-service-icon service-icon"></div>
                        <h4>Hilfe anbieten</h4>
                        <p>
                            Möchtest du Atemschutzmasken, Handschuhe oder sonstige Schutzartikel spenden, dann trage
                            Diese bitte hier ein:
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
    <section class="section fall" data-section="actuallCases" id="actuallCases">
        <div class="container">
            <div class="section-heading">
                <h2>Einblicke</h2>
                <div class="line-dec"></div>
                <span>
Hier siehst du ein paar Einblicke in unsere Plattform.
</span>
                <div class="isotope-wrapper">
                    <div class="isotope-box">
                        <div class="isotope-item" data-type="people">
                            <figure class="snip1321">
                                <img
                                        src="assets/images/portfolio1.PNG"
                                        alt="Übersichtsseite"
                                />
                                <figcaption>
                                    <a
                                            href="assets/images/portfolio1.PNG"
                                            data-lightbox="image-1"
                                            data-title=""
                                    ><i class="fa fa-search"></i
                                        ></a>
                                    <h4>Übersichtsseite</h4>
                                    <span>hier sieht man die Übersicht über seine eigene Angebote oder seinen Bedarf.</span>
                                </figcaption>
                            </figure>
                        </div>


                        <div class="isotope-item" data-type="nature">
                            <figure class="snip1321">
                                <img
                                        src="assets/images/portfolio2.PNG"
                                        alt="Bedarfseite"
                                />
                                <figcaption>
                                    <a
                                            href="assets/images/portfolio2.PNG"
                                            data-lightbox="image-1"
                                            data-title="Atemschutzmasken der Schutzklasse FFP-2"
                                    ><i class="fa fa-search"></i
                                        ></a>
                                    <h4>Bedarfseite</h4>
                                    <span>Hier sieht man jeden Bedarf, der erstellt wurde und kann speziell suchen.</span>
                                </figcaption>
                            </figure>
                        </div>
                        <div class="isotope-item" data-type="animals">
                            <figure class="snip1321">
                                <img
                                        src="assets/images/portfolio3.PNG"
                                        alt="Bearbeitungsseite"
                                />
                                <figcaption>
                                    <a
                                            href="assets/images/portfolio3.PNG"
                                            data-lightbox="image-1"
                                            data-title="Fallzahlen in Deutschland"
                                    ><i class="fa fa-search"></i
                                        ></a>
                                    <h4>Bearbeitungsseite</h4>
                                    <span>Hier kann man seine Institution bearbeiten und Daten aktualisieren.</span>
                                </figcaption>
                            </figure>
                        </div>


                        <div class="isotope-item" data-type="people">
                            <figure class="snip1321">
                                <img
                                        src="assets/images/portfolio4.PNG"
                                        alt="Angebotsseite"
                                />
                                <figcaption>
                                    <a
                                            href="assets/images/portfolio4.PNG"
                                            data-lightbox="image-1"
                                            data-title="Caption"
                                    ><i class="fa fa-search"></i
                                        ></a>
                                    <h4>Angebotsseite</h4>
                                    <span>Hier sieht man alle Angebote und kann diese Suchen oder ein neues anlegen.</span>
                                </figcaption>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <section class="section contact-us" data-section="contact" id="contact">
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
                        <form id="contact" action="index.php" method="post" onSubmit="return eingaben_ueberpruefen();"
                              name="contact">
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
                                    Wenn Sie die Nachricht senden akzeptieren Sie unsere <a href="dataprotection.html">Datenschutzerklärung</a>
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
<?php include(__DIR__ . '/src/includes/footer.html'); ?>