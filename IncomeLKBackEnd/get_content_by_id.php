<?php
header('Access-Control-Allow-Origin: *'); 
if (empty($_POST["content_id"])) {
    $output["message"] = "failed";
    $output["content"] = "Email field is empty";
}
else {
    $contentID =  $_POST["content_id"];
    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    $result = $access->getContentById($contentID);
    if ($result["message"] == "success"){
        $returnArray = [];
        $returnArray["message"] = "success";
        $returnArray["details"] = "Content details avilable";
        $returnArray["content"] = $result["content"];
        echo json_encode($result);
    }
    else {
        $returnArray = [];
        $returnArray["message"] = "failed";
        $returnArray["details"] = "Your content is not available";
        $returnArray["content"] = $result;
        echo json_encode($returnArray);
    }
}


?>