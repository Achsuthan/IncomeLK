<?php
header('Access-Control-Allow-Origin: *'); 

    $inputJSON = file_get_contents('php://input');
    $ideabiz = $inputJSON;//json_decode($inputJSON, TRUE);

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

?>