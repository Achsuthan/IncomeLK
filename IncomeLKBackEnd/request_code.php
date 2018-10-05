<?php
if (empty($_POST["email"])) {
    $output["message"] = "failed";
    $output["content"] = "Email field is empty";
} 
else {
    $email = $_POST["email"];
    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    $result = $access->requestCode($email);
    print_r($result);
    if ($result == 1){
        $returnArray = [];
        $returnArray["message"] = "success";
        $returnArray["details"] = "Check your mail for get a OTP password";
        echo json_encode($returnArray);
    }
    else {
        echo json_encode($result);
    }
}
?>