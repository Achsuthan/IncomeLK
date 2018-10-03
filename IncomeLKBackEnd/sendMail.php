<?php
//MARK: - Sending Mail

/*$email="achsuthan@icloud.com";
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
*/


//MARK :- Getting the header form request to check the token
/*$headers = apache_request_headers();
$token123 = $headers['token'];
print($token123);
*/



//MARK: - create JWT Token by usign secret key
/*require("Secure/jwt_helper.php");
$token = array();
$token['phone'] = "Achsuthan";
$token['code'] = "1212";
$token['iat'] = 1538340754;
$token['exp'] = 1538344355;
echo JWT::encode($token, 'secret_server_key');
*/




//MARK: - Decode Token with secret Key
/*$token = JWT::decode('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IkFjaHN1dGhhbiJ9.Cr0bK-CoSycgMYLGcatf85TbcqSU5M5gWth_QaAmcIa','secret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_keysecret_server_key',true);
if ($token != false) {
    print("token ".$token->id);
}
else {
    print("Wrong Key");
}*/

?>