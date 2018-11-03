<?php
header('Access-Control-Allow-Origin: *'); 

//access the Test.ini file
$file = parse_ini_file("Test.ini"); //get the database name,username ,password values

//get the values form Test.ini and assign those values to the variable
    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);


//require the access.php file to call the function for the future purpose
    require("Secure/access.php");

//call the class and assign the values get from the Test.ini
    $access = new access($host, $user, $pass, $name);

//call the connect function to connect with the database
    //$access->connect();

//$access->connect();


$access->getDialogToken();

$currentTime = $updated_date = strtotime(date("Y-m-d\TH:i:s\Z"))*1000  - 86400000;

echo $currentTime;

//1541265406000

//$access->sendMail("achsuthan@icloud.com","sa","dsf");

// $data = array("method" => "ANC", "msisdn" => "94774455878"); 
// $data = json_encode($data);
// $access->APICall("",$data,"")

// $plain_txt = "IncomeLK";
// echo "Plain Text =" .$plain_txt. "\n";
// $encrypted_txt = $access->encrypt_decrypt('encrypt', $plain_txt);
// echo "Encrypted Text = " .$encrypted_txt. "\n";
// $decrypted_txt = $access->encrypt_decrypt('decrypt', $encrypted_txt);
// echo "Decrypted Text =" .$decrypted_txt. "\n";
// if ( $plain_txt === $decrypted_txt ) echo "SUCCESS";
// else echo "FAILED";
// echo "\n";





//MARK: -- DO NOT TOUCH ON THIS CODE IF U TOUCH IT U WILL DIE



//MARK :- IMPORTANT Getting the header form request to check the token
/*$headers = apache_request_headers();
$token123 = $headers['token'];
print($token123);
*/


//MARK: - IMPORTANT check dates
//$access->checkDates(date("Y-m-d\TH:i:s\Z",strtotime('2018-10-04T00:45:00Z')));


?>