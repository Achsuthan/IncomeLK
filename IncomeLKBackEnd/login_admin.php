<?php
header('Access-Control-Allow-Origin: *'); 
if (empty($_POST["email"]) && empty($_POST["otp"])) {
    $output["message"] = "failed";
    $output["content"] = "Email field is empty";
} 
else {
    $email = $_POST["email"];
    $otp = $_POST["otp"];
    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    $result = $access->loginAdmin($email,$otp);

    if ($result == 1){
        $returnArray = [];
        $returnArray["message"] = "success";
        $returnArray["details"] = "Welcome to Income.lk";
        echo json_encode($returnArray);
    }
    else {
        echo json_encode($result);
    }
}
?>