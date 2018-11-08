<?php
header('Access-Control-Allow-Origin: *'); 
if (empty($_POST["ideabiz"])) {
    $output["message"] = "failed";
    $output["content"] = "ideabiz field is empty";
    echo json_encode($output);
}
else {

    $ideabiz = $_POST["ideabiz"];
    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    //$access = new access($host, $user, $pass, $name);

    //$access->connect();
    
    $file = fopen("ideabiz.txt","r");
    $val = "";
    if (filesize("ideabiz.txt") > 0){
        $val = fread($file,filesize("ideabiz.txt"));
    }
    else {
        $val = fread($file,"1");
    }
    
    fclose($file);

    $myFile = "ideabiz.txt";
    $fh = fopen($myFile, 'w') or die("can't open file");
    
    $new_val = "$val \n\n $ideabiz";
    fwrite($fh, $new_val);
    fclose($fh);

    echo $ideabiz;

   // $phone = substr($phone,1);
    //$result = $access->checkSubscribedUser("94$phone");
    // if ($result == 1){
    //     $returnArray = [];
    //     $returnArray["message"] = "success";
    //     $returnArray["details"] = "User Available";
    //     echo json_encode($returnArray);
    // }
    // else {
    //     $returnArray = [];
    //     $returnArray["message"] = "failed";
    //     $returnArray["details"] = "User Not Available";
    //     echo json_encode($returnArray);
    // }
}