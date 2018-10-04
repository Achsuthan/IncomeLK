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

    $result = $access->registerAdmin($email);

    if ($result){
        echo "success";
    }
    else {
        echo "fail";
    }
}
?>