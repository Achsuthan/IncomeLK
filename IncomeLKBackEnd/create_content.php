<?php 
if (empty($_POST["english"]) && empty($_POST["sinhala"]) && empty($_POST["type"]) && empty($_POST["heading"])) {
    $output["message"] = "failed";
    $output["content"] = "Email field is empty";
} 
else {
    $english = $_POST["english"];
    $sinhala = $_POST["sinhala"];
    $type = $_POST["type"];
    $heading = $_POST["heading"];

    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    $result = $access->createContent($english,$sinhala,$type,$heading);
    if ($result == 1){
        $returnArray = [];
        $returnArray["message"] = "success";
        $returnArray["details"] = "Your Content created successfully";
        echo json_encode($returnArray);
    }
    else {
        echo json_encode($result);
    }
}
?>