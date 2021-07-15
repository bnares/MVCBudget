<?php

namespace App;

require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");



$mail = new PHPMailer\PHPMailer\PHPMailer();

$mail->IsSMTP();
$mail->CharSet="UTF-8";
$mail->Host = "smtp.gmail.com"; /* Zależne od hostingu poczty*/
$mail->SMTPDebug = 1;
$mail->Port = 465 ; /* Zależne od hostingu poczty, czasem 587 */
$mail->SMTPSecure = 'ssl'; /* Jeżeli ma być aktywne szyfrowanie SSL */
$mail->SMTPAuth = true;
$mail->IsHTML(true);
$mail->Username = "piotr.ostrouch@gmail.com"; /* login do skrzynki email często adres*/
$mail->Password = "haslo"; /* Hasło do poczty */
$mail->setFrom('piotr.ostrouch@gmail.com', 'Piotr Ostrouch'); /* adres e-mail i nazwa nadawcy */
$mail->AddAddress("piotr.ostrouch@gmail.com"); /* adres lub adresy odbiorców */
$mail->Subject = "Testowa wiadomość SMTP"; /* Tytuł wiadomości */
$mail->Body = "Witaj, Jeżeli to czytasz, to znaczy, że udało się poprawnie wysłać e-maila za pomocą SMTP!";

if(!$mail->Send()) {
echo "Błąd wysyłania e-maila: " . $mail->ErrorInfo;
} else {
echo "Wiadomość została wysłana!";
}

