<?php 
header('Access-Control-Allow-Origin: *'); 
$file = parse_ini_file("Test.ini");

$host = trim($file["dbhost"]);
$user = trim($file["dbuser"]);
$pass = trim($file["dbpass"]);
$name = trim($file["dbname"]);

require("Secure/access.php");
$access = new access($host, $user, $pass, $name);

$access->connect();

$result = $access->getDashboardDetails();
echo json_encode($result);
?>