<?php 
header('Access-Control-Allow-Origin: *'); 
if (empty($_POST["phone"]) && empty($_POST["otp"])) {
    $output["message"] = "failed";
    $output["content"] = "Email field is empty";
} 
else {
    $phone = $_POST["phone"];
    $otp = $_POST["otp"];

    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    $phone = substr($phone,1);
    $result = $access->OTPVerfication("94$phone",$otp);
    if ($result == 1){
        $returnArray = [];
        $returnArray["message"] = "success";
        $returnArray["details"] = "User Successfully verified";
        echo json_encode($returnArray);
    }
    else {
        echo json_encode($result);
    }
}
?>