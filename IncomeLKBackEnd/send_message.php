<?php
header('Access-Control-Allow-Origin: *'); 
if (empty($_POST["name"]) && empty($_POST["email"]) && empty($_POST["message"])) {
    $output["message"] = "failed";
    $output["content"] = "Email field is empty";
    echo json_encode($output);
}
else {

    $phone = "null";
    if (!empty($_POST["phone"])) {
        $phone = $_POST["phone"];
    }
    $Username =  $_POST["name"];
    $email =  $_POST["email"];
    $message =  $_POST["message"];
    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    $result = $access->sendMessage($Username, $phone, $email, $message);
    if ($result["message"] == "success"){
        $returnArray = [];
        $returnArray["message"] = "success";
        $returnArray["details"] = "Message send Successfully";
        echo json_encode($returnArray);
    }
    else {
        $returnArray = [];
        $returnArray["message"] = "failed";
        $returnArray["details"] = "Message not sent";
        $returnArray["content"] = $result;
        echo json_encode($returnArray);
    }
}


?>