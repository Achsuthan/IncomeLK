<?php
$email="achsuthan@icloud.com";
$emailbody ="hello";

require("vendor/autoload.php");
require_once('vendor/phpmailer/phpmailer/PHPMailerAutoload.php');

$mail = new PHPMailer();

//$mail->SMTPDebug = 2;
$mail->isSMTP();
$mail->Host = "ssl://smtp.gmail.com:465";

$mail->SMTPAuth = true;
$mail->Password = "achsuthan4455878";
$mail->SMTPSecure = "ssl";
$mail->Port = 465;
$mail->Username = "achsuthancopy9314@gmail.com";
$mail->From = "achsuthancopy9314@gmail.com";
$mail->FromName = $email;;
$mail->addAddress($email, "");
$mail->isHTML(true);
$mail->Subject = "Activaion";
$mail->Body=$emailbody;


$mail->AltBody = " ";


if (!$mail->send()) {
    //echo "Mailer Error: " . $mail->ErrorInfo;
    $status="Fail";
} else {
    //echo "Message has been sent successfully";
    $status="Success";
}
print($status);

?>