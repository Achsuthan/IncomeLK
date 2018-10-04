<?php 
if (empty($_POST["english"]) && empty($_POST["sinhala"]) && empty($_POST["type"]) && empty($_POST["content_id"])) {
    $output["message"] = "failed";
    $output["content"] = "Email field is empty";
} 
else {
    $english = $_POST["english"];
    $sinhala = $_POST["sinhala"];
    $type = $_POST["type"];
    $contentID = $_POST["content_id"];

    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    $result = $access->updateContent($english,$sinhala,$type,$contentID);
    if ($result){
        echo "success";
    }
    else {
        echo "fail";
    }
}
?>