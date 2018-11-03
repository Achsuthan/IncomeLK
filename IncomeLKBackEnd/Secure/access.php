<?php

//this class is going to interact with the database
class access
{
	
	//c	reate needed variable for this class
	var $host=null;
    var $user=null;
    var $pass=null;
    var $dbname=null;
    var $con=null;
    var $result=null;
    var $payment;
    var $hand;
    var $submitted;
    var $uid;


    function __construct($dbhost,$dbuser,$dbpass,$dbname)   //get the values from the caller
    {
        $this->host= $this->encrypt_decrypt("decript",$dbhost);   //assgin the hostname
        $this->user= $this->encrypt_decrypt("decript",$dbuser);   //assign the username
        $this->pass= $this->encrypt_decrypt("decript",$dbpass);   //assign the password
        $this->dbname= $this->encrypt_decrypt("decript",$dbname);  //assgin the db name
    }

    public function connect()   //DB connection
    {
        $this->con=new mysqli($this->host,$this->user,$this->pass,$this->dbname);  //get the host,user,password and the dbname from the caller
        if(mysqli_connect_error())  //check whether the db connection contain any error
        {
            echo "Could no connect databe"; //prompt the error message
        }
        $this->con->set_charset("utf8");
    }

    //disconnect the db connection
    public function disconnect()
    {
        if($this->con!=null)  //check whether the con variable contain any value
        {
            $this->con->close();  //close the db connection
        }
    }

    //MARK :- SENDING MAIL
    public function sendMail($receiver, $body, $subject){
        //MARK: - Sending Mail

        // require("vendor/autoload.php");
        // require_once('vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
        // $mail = new PHPMailer();

        // $mail->isSMTP();
        // $mail->Host       = "ssl://smtp.gmail.com:465";
        // $mail->SMTPAuth   = true;
        // $mail->Password   = "achsuthan4455878";
        // $mail->SMTPSecure = "ssl";
        // $mail->Port       = 465;
        // $mail->Username   = "achsuthancopy9314@gmail.com";
        // $mail->From       = "noreplay@income.lk";
        // $mail->FromName   = $receiver;
        // $mail->addAddress($receiver, "");
        // $mail->isHTML(true);
        // $mail->Subject = $subject;
        // $mail->Body    = $body;
        // $mail->AltBody = " ";
        // if (!$mail->send()) {
        //     $status = true;
        // } else {
        //     $status = false;
        // }

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: noReply@income.lk' . "\r\n";
        $status = mail($receiver,$subject,$body,$headers);
        return $status;
    }

    //MARK:- ENCODE JWT
    public function encodeJWT($phone_number, $code){
        //MARK: - create JWT Token by usign secret key
        require("jwt_helper.php");
        $token = array();
        $token['phone'] = $phone_number;
        $token['code'] = $code;

        $current_time = strtotime(date("Y-m-d\TH:i:s\Z", strtotime("now"))); // get unix timestamp
        $current_time_in_millisecond = $current_time*1000;

        print("current time".$current_time_in_millisecond);

        $after_30_minutes = strtotime(date("Y-m-d\TH:i:s\Z", strtotime("+30 minutes")));
        $after_30_minutes_in_milisecond = $after_30_minutes*1000 ;

        print("After 30 Minutes ".$after_30_minutes_in_milisecond);
        $token['iat'] = $current_time_in_millisecond;
        $token['exp'] = $after_30_minutes_in_milisecond;
        echo JWT::encode($token, 'secret_server_key');

    }

    //MARK:- DECODE JWT
    public function decodeJWT($header_string){
        //MARK: - Decode Token with secret Key
        $token = JWT::decode($header_string,'secret_server_key',true);
        if ($token != false) {
            print("phone ".$token->phone);
        }
        else {
            print("Wrong Key");
        }
    }

    //MARK:- CHECK DATES
    public function checkDates($date){
        $current_time_in_millisecond = strtotime(date("Y-m-d\TH:i:s\Z"));
        print($date);
        if ($current_time_in_millisecond*1000 > strtotime($date)*1000) {
            print("time Expred");
        }
        else {
            print("time is not expred");
        }
    }
    

    public function registerAdmin($email){
        $created_date = strtotime(date("Y-m-d\TH:i:s\Z"))*1000;
        
        $sql    = "Select * from admin Where email = '".$email."' ORDER BY id DESC LIMIT 1; ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                echo "Row availble";
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "This user already exist, please try with different email";
                return $returnArray;                 //asign back to id as a USR111112
            }
            else {
                $sql = "INSERT INTO admin (email, created_date, updated_date,is_enable)
                VALUES ('".$email."', '".$created_date."', '','1')";
                if ($this->con->query($sql) === TRUE) {
                    return true;
                } else {
                    $returnArray = [];
                    $returnArray["message"] = "failed";
                    $returnArray["details"] = "Something went wrong please try again a bit";
                    return $returnArray;
                }
            }
        }
        else {
            $sql = "INSERT INTO admin (email, created_date, updated_date,is_enable)
                VALUES ('".$email."', '".$created_date."', '','1')";
                if ($this->con->query($sql) === TRUE) {
                    return true;
                } else {
                    $returnArray = [];
                    $returnArray["message"] = "failed";
                    $returnArray["details"] = "Something went wrong please try again a bit";
                    return $returnArray;
                }
        }
    }

    public function loginAdmin($email,$otp){
        if ($this->checkOTP($email,$otp)){
            $delete_sql = "DELETE FROM otp_password WHERE admin_email= '".$email."'";
            if ($this->con->query($delete_sql) === TRUE) {
                $updated_date = strtotime(date("Y-m-d\TH:i:s\Z"))*1000;
                $sql = "UPDATE admin SET updated_date='".$updated_date."' WHERE email='".$email."'";
                if ($this->con->query($sql) === TRUE) {  
                    return true;
                } else {
                    $returnArray = [];
                    $returnArray["message"] = "failed";
                    $returnArray["details"] = "Something went wrong please try agian a bit";
                    return $returnArray;
                }
            } else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Something went wrong please try again a bit";
                return $returnArray;
            } 
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Enter correct OTP password";
            return $returnArray;
        }
    }

    public function requestCode($email){
        $sql    = "Select * from admin Where email = '".$email."' ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                return $this->createOTP($email);                 //asign back to id as a USR111112
            }
            else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Something went wrong please try again a bit";
                return $returnArray;
            }
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Your email address is wrong";
            return $returnArray;
        }
    }

    public function createOTP($email){
        
        $password = $otp_code = strtoupper(substr(md5(uniqid()), 0, 6)); 
        if ($this->CheckEmailInOTP($email)) {
            $sql = "UPDATE otp_password SET otp='".$password."' WHERE admin_email='".$email."'";
            if ($this->con->query($sql) === TRUE) {  
                 $this->sendMail($email,$password,"OTP Password");
                 return true;
            } else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Something went wrong please try again a bit";
                return $returnArray;
            }
        }
        else {
            $sql = "INSERT INTO otp_password (admin_email, otp)
            VALUES ('".$email."', '".$password."')";
            if ($this->con->query($sql) === TRUE) {
                $this->sendMail($email,$password,"OTP Password");
                return true;
            } else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Something went wrong please try again a bit";
                return $returnArray;
            }
            
        }
    }

    public function CheckEmailInOTP($email){
        $sql    = "Select * from otp_password Where admin_email = '".$email."' ORDER BY id DESC LIMIT 1; ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                return true;                  //asign back to id as a USR111112
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function checkOTP($email, $otp){
        $sql    = "Select * from otp_password where admin_email = '".$email."' and otp = '".$otp."'  ORDER BY id DESC LIMIT 1; ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function createContent($english, $sinhala, $type, $heading){
        $contentID = $this->createContentID();
        $created_date = strtotime(date("Y-m-d\TH:i:s\Z"))*1000;
        $updated_date = $created_date;
        $file_pth = "/uploads/$contentID.jpg";
        $sql = "INSERT INTO content (content_id, image_url, english,sinhala,type,created_date, updated_date, heading)
        VALUES ('".$contentID."', '".$file_pth."', '".$english."','".$sinhala."','".$type."','".$created_date."','".$created_date."', '".$heading."')";
        if ($this->con->query($sql) === TRUE) {
            $returnArray = [];
            $returnArray["message"] = "success";
            $returnArray["content_id"] = $contentID;
            return $returnArray;
        } else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Something went wrong please try again a bit";
            return $returnArray;
        }
    }

    public function createContentID()
    {
        $sql    = "Select * from content ORDER BY content_id DESC LIMIT 1; ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                //echo substr('abcdef', 1, 3);  // bcd
                $id        = substr($row["content_id"], 3, 6);  //get the integer potion part for  fro example if the database contain a uid USR111111, get the last 6 digit
                $id        = $id+1;                      //increase the last 6 digit value by one
                return "CON".$id;                  //asign back to id as a USR111112
            }
            else {
                return"CON111111";
            }
        }
        else {
            return "CON111111";
        }
    }


    public function updateContent($english, $sinhala, $type, $contentID,$heading){
        if ($this->checkContent($contentID)){
            $updated_date = strtotime(date("Y-m-d\TH:i:s\Z"))*1000;
            $sql = "UPDATE content SET english='".$english."', sinhala = '".$sinhala."', type = '".$type."', updated_date = '".$updated_date."', heading= '".$heading."' WHERE content_id='".$contentID."'";
            if ($this->con->query($sql) === TRUE) {  
                return true;
            } else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Something went wrong try again a bit";
                return $returnArray;
            }
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Your content is not available";
            return $returnArray;
        }
    }

    public function checkContent($contentID){
        $sql    = "Select * from content where content_id = '".$contentID."' ORDER BY content_id DESC LIMIT 1; ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function deleteContent($contentID){
        if ($this->checkContent($contentID)){
            $delete_sql = "DELETE FROM content WHERE content_id= '".$contentID."'";
            if ($this->con->query($delete_sql) === TRUE) {
                if (file_exists("../uploads/".$contentID."jpg")){
                    unlink("../uploads/".$contentID."jpg");
                }
                return true;
            } else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Something went wrong please try again a bit";
                return $returnArray;
            } 
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Your Content is not available";
            return $returnArray;
        }
    }

    public function updateContentDate($contentID){
        if ($this->checkContent($contentID)){
            $updated_date = strtotime(date("Y-m-d\TH:i:s\Z"))*1000;
            $sql = "UPDATE content SET updated_date = '".$updated_date."' WHERE content_id='".$contentID."'";
            if ($this->con->query($sql) === TRUE) {  
                return true;
            } else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Something went wrong please try agin a bit";
                return $returnArray;
            }
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Your Content is not available";
            return $returnArray;
        }
    }

    public function getAllContent(){
        $sql    = "Select * from content ORDER BY updated_date DESC";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $resultArray["message"] = "success";
            $returnArray["details"] = "Content details avilable";

            $tResult = [];

            while($row = $result->fetch_assoc()) {
                array_push($tResult, $row);
            }
            $resultArray["content"] = $tResult;
            return $resultArray;
            
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "There is no content available right now try again a bit";
            return $returnArray;
        }
    }

    public function getContent(){
        $sql    = "Select * from content ORDER BY updated_date DESC";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $resultArray = [];
            while($row = $result->fetch_assoc()) {
                $tmpArray["heading"] = $row["heading"];
                $tmpArray["image"] = $row["image_url"];
                $tmpArray["type"] = $row["type"];
                array_push($resultArray, $tmpArray);
            }
            return $resultArray;
            
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "There is no content available right now try again a bit";
            return $returnArray;
        }
    }

    public function getAllAdmin(){
        $sql    = "Select * from admin";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $resultArray = [];
            while($row = $result->fetch_assoc()) {
                $tmpArray["email"] = $row["email"];
                array_push($resultArray, $tmpArray);
            }
            return $resultArray;
            
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "There is no content available right now try again a bit";
            return $returnArray;
        } 
    }

    public function deleteAdmin($email){
        $sql    = "Select * from admin Where email = '".$email."' ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                $delete_sql = "DELETE FROM admin WHERE email= '".$email."'";
                if ($this->con->query($delete_sql) === TRUE) {
                    return true;
                } else {
                    $returnArray = [];
                    $returnArray["message"] = "failed";
                    $returnArray["details"] = "Something went wrong please try again a bit";
                    return $returnArray;
                } 
            }
            else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Admin not available";
                return $returnArray;
            }
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Something went wrong please try again a bit";
            return $returnArray;
        }
    }

    public function getContentById($contentID){
        $sql    = "Select * from content Where content_id = '".$contentID."' ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                $returnArray = [];
                $returnArray["message"] = "success";
                $returnArray["content"] = $row;
                return $returnArray;
            }
            else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Admin not available";
                return $returnArray;
            }
        }
        else {
            $returnArray = [];
            $returnArray["message"] = "failed";
            $returnArray["details"] = "Something went wrong please try again a bit";
            return $returnArray;
        }
    }

    public function sendMessage($name, $phone, $email, $message){

            $sql    = "Select * from admin";  //get the last value form the database
            $result = $this->con->query($sql);                          //get the result by executing the sql query
            if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
                while($row = $result->fetch_assoc())  //check whether the variable row contain value or not
                {
                    $adminEmail = $row["email"];
                    $this->sendMail("$adminEmail","The User $name with Email $email and phone Number $phone send you a message - $message","User Send Email");
                }

                $returnArray ["message"] = "success";
                return $returnArray;
            }
            else {
                $returnArray = [];
                $returnArray["message"] = "failed";
                $returnArray["details"] = "Something went wrong please try again a bit";
                return $returnArray;
            }
        

    }

    public function requestOTP($OTP){

    }

    public function OTPVerfication($phone, $OTP){

    }

    public function getDialogToken(){

    }

    public function APICall($urll,$parameter, $method){
        $url = "https://ideabiz.lk/apicall/pin/subscription/v1/subscribe";
        $curl = curl_init();

    // switch ($method)
    // {
    //     case "POST":
    //         curl_setopt($curl, CURLOPT_POST, 1);

    //         if ($data)
    //             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    //         break;
    //     case "PUT":
    //         curl_setopt($curl, CURLOPT_PUT, 1);
    //         break;
    //     default:
    //         if ($data)
    //             $url = sprintf("%s?%s", $url, http_build_query($data));
    // }


     //$data = "grant_type=password&username=Storytellers&password=Jeevithan5&scope=PRODUCTION";
     //$data = "method=ANC&msisdn=94774455878";

    //  $data = array("method" => "ANC", "msisdn" => "94774455878"); 
    //  $data = json_encode($data);

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $parameter );
    // 'Content-Type: application/x-www-form-urlencoded',

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer 27bedaeb8c117c964acc29516d986479',
        'Accept: application/json'
    ];

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // Optional Authentication:
    // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    print_r($curl);
    $result = curl_exec($curl);

    curl_close($curl);

    
    $result = json_decode($result,true);
    print_r($result);
    $accessToken = $result["access_token"];
    if (isset($result["hello"])){
        echo "Vlaue found";
    }
    else {
        echo "value not found";
    }
    echo "Hello $accessToken";
    return $result;
    }


    function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'jeevithan_secret_ley';
        $secret_iv = 'jeevithan_secret_iv';
        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

}
?>