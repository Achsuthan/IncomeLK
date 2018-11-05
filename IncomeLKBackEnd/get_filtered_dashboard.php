<?php
header('Access-Control-Allow-Origin: *'); 
if (empty($_POST["start"]) && empty($_POST["end"])) {
    $output["message"] = "failed";
    $output["content"] = "Email field is empty";
} 
else {
    $start = $_POST["start"];
    $end = $_POST["end"];
    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    

    $start = strtotime(date($start))*1000;
    $end = strtotime(date($end))*1000;

    $result = $access->getFilteredDashboard($start,$end);
    echo json_encode($result);
}
?>