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
    $access->getDialogToken();
    $access->sendMail("achsuthan@icloud.com","IdeaBiz","Dialog Token Refereshed");

?>