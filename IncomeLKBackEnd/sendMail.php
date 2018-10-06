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


$contentID = "CON111111";
$fileName = "./uploads/$contentID.jpg";
print($fileName);

if (file_exists($fileName)){
   
    unlink($fileName);
}
else {
    echo "hello";
}





//MARK: -- DO NOT TOUCH ON THIS CODE IF U TOUCH IT U WILL DIE



//MARK :- IMPORTANT Getting the header form request to check the token
/*$headers = apache_request_headers();
$token123 = $headers['token'];
print($token123);
*/


//MARK: - IMPORTANT check dates
//$access->checkDates(date("Y-m-d\TH:i:s\Z",strtotime('2018-10-04T00:45:00Z')));


?>