<?php
header('Access-Control-Allow-Origin: *'); 
if (empty($_POST["content_id"])){
    echo "failed";
}
else {
    $contentID = $_POST["content_id"];
    $file = parse_ini_file("Test.ini");

    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pass = trim($file["dbpass"]);
    $name = trim($file["dbname"]);

    require("Secure/access.php");
    $access = new access($host, $user, $pass, $name);

    $access->connect();

    $result = $access->checkContent($contentID);
    if ($result){
        $target_dir = "./uploads/";
        $target_file = $target_dir .$contentID.".jpg";
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if (file_exists($target_file)) {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Sorry, file already exists.";
            echo json_encode($returnArray);
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Sorry, your file is too large";
            echo json_encode($returnArray);
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed";
            echo json_encode($returnArray);
            
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Your file was not uploaded";
            echo json_encode($returnArray);
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $returnArray = [];
                $returnArray["message"] = "success";
                $returnArray["details"] = "Your file ".basename($_FILES["fileToUpload"]["name"]." has been uploaded");
                echo json_encode($returnArray);
            } else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Sorry, there was an eror uploading file try again a bit";
                echo json_encode($returnArray);
            }
        }
    }
    else {
        $returnArray = [];
        $returnArray["message"] = "failed";
        $returnArray["details"] = "Your content is not avilable";
        echo json_encode($returnArray);
    }
    
}

?>