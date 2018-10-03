<?php

//this class is going to interact with the database
class access
{
	
	//c	reate needed variable for this class
	
	var $host   = null;
	var $user   = null;
	var $pass   = null;
	var $dbname = null;
	var $con    = null;
	var $result = null;
	var $payment;
	var $hand;
	var $submitted;
	var $uid;
	
	
	function __construct($dbhost,$dbuser,$dbpass,$dbname)   //g	et the values from the caller
																																																											    {
		$this->host = $dbhost;
		//a		ssgin the hostname
																																																																																																																						        $this->user = $dbuser;
		//a		ssign the username
																																																																																																																						        $this->pass = $dbpass;
		//a		ssign the password
																																																																																																																						        $this->dbname = $dbname;
		//a		ssgin the db name
	}
	
	public function connect()   //D	B connection
																																																											    {
		$this->con = new mysqli($this->host,$this->user,$this->pass,$this->dbname);
		//g		et the host,user,password and the dbname from the caller
																																																																																																																						        if(mysqli_connect_error())  //c		heck whether the db connection contain any error
																																																																																																																						        {
			echo "Could no connect databe";
			//p			rompt the error message
		}
		$this->con->set_charset("utf8");
	}
	
	//d	isconnect the db connection
																																																											    public function disconnect()
																																																											    {
		if($this->con!=null)  //c		heck whether the con variable contain any value
																																																																																																																						        {
			$this->con->close();
			//c			lose the db connection
		}
	}
	
	
	
	public function check_username_password($username,$password)  //g	etting the values
																																																											    {
		$sql = "select * from user where username='".$username."' and password='".$password."'";
		//s		ql query for get the user's details with username and password
        $result = $this->con->query($sql);  //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the row values from database
            if(!empty($row))      //check whether the row value is null or not
            {
                $returnArray = $row;  // assign the row values to the retrunarray
            }
            return $returnArray;   //return the returnarry to caller
        }
    }
    //Register user
    public function insert_check($username,$password,$email,$firstname,$lastname,$person)
    {
        if($person=="user") {
            $this->createid_user();  //crate auto increment id for the user
            $sql       = "INSERT INTO user SET username=?,password=?,email=?,firstname=?,lastname=?,uid=?";  //sql query for insert values to the database
            $statement = $this->con->prepare($sql);                                                          //get the statement executing the sql query
            if (!$statement)    //check whether the statement contain any results
            {
                throw new Exception($statement->error);   //error message
            }
            $statement->bind_param("ssssss", $username, $password, $email, $firstname, $lastname, $this->uid);  //pass the values
            $returnvalue = $statement->execute();  //executing the sql query
        }
        else if($person=="admin")
        {
            $this->createid_admin();  //crate auto increment id for the user
            $sql       = "INSERT INTO admin SET username=?,password=?,email=?,firstname=?,lastname=?,aid=?";  //sql query for insert values to the database
            $statement = $this->con->prepare($sql);                                                           //get the statement executing the sql query
            if (!$statement)    //check whether the statement contain any results
            {
                throw new Exception($statement->error);   //error message
            }
            $statement->bind_param("ssssss", $username, $password, $email, $firstname, $lastname, $this->uid);  //pass the values
            $returnvalue = $statement->execute();  //executing the sql query
        }
        return 1;   //return the caller to notify that the user is inserted successfully
    }
    //creating userid with specific string and number
    public function create_id_check()
    {
        $sql    = "Select * from user ORDER BY id DESC LIMIT 1; ";  //get the last value form the database
        $result = $this->con->query($sql);                          //get the result by executing the sql query
        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);  //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                //echo substr('abcdef', 1, 3);  // bcd
                $id        = substr($row["uid"], 3, 6);  //get the integer potion part for  fro example if the database contain a uid USR111111, get the last 6 digit
                $id        = $id+1;                      //increase the last 6 digit value by one
                $this->uid = "USR".$id;                  //asign back to id as a USR111112
            }
        }
    }
    public function check_a_vaue_check($username)
    {
        $sql    = "select * from user where username='".$username."'";  //sql query for get the user's details with username and password
																																																										        $result = $this->con->query($sql);
		//g		et the result by executing the sql query
		
		if ($result !=null && (mysqli_num_rows($result)>=1))  //c		heck the query contain the result or not
																																																																																																																						        {
			$row = $result->fetch_array(MYSQLI_ASSOC);
			//g			et the row values from database
																																																																																																																																																																																	            if(!empty($row))      //c			heck whether the row value is null or not
																																																																																																																																																																																	            {
				$returnArray = $row;
				// 				assign the row values to the retrunarray
			}
			return $returnArray;
			//r			eturn the returnarry to caller
		}
		else
																																																																																																																						        {
			$sql = "select * from admin where username='".$username."'";
			//s			ql query for get the user's details with username and password
            $result = $this->con->query($sql);  //get the result by executing the sql query
            if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
            {
                $row = $result->fetch_array(MYSQLI_ASSOC);  //get the row values from database
                if(!empty($row))      //check whether the row value is null or not
                {
                    $returnArray = $row;  // assign the row values to the retrunarray
                }
                return $returnArray;   //return the returnarry to caller
            }
        }
    }
    public function get_all_values_check()
    {
        $returnArray = array();
        $sql         = "select COUNT(*) as mal,malware,date from user_file  group by date,malware";
        //$sql = "select COUNT(*) as mal,malware, date from user_file WHERE  group by date,malware";
        $result = $this->con->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[] = $row;
            }
        }
    }
    public function send_mail($receiver, $body, $subject){
        //MARK: - Sending Mail
        require("vendor/autoload.php");
        require_once('vendor/phpmailer/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer();
        //$mail->SMTPDebug = 2;

        $mail->isSMTP();
        $mail->Host       = "ssl://smtp.gmail.com:465";
        $mail->SMTPAuth   = true;
        $mail->Password   = "achsuthan4455878";
        $mail->SMTPSecure = "ssl";
        $mail->Port       = 465;
        $mail->Username   = "achsuthancopy9314@gmail.com";
        $mail->From       = "noreplay@income.lk";
        $mail->FromName   = $receiver;
        $mail->addAddress($receiver, "");
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = " ";
        if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
            $status = "Fail";
        } else {
            //echo "Message has been sent successfully";
            $status = "Success";
        }
        print($status);
    }

    public function encodeJWT($phone_number, $code){
        //MARK: - create JWT Token by usign secret key
        require("jwt_helper.php");
        $token = array();
        $token['phone'] = $phone_number;
        $token['code'] = $code;

        $current_time = strtotime(date("Y/m/d H:i:s", strtotime("now"))); // get unix timestamp
        $current_time_in_millisecond = $current_time*1000;

        print("current time".$current_time_in_millisecond);

        $after_30_minutes = strtotime(date("Y/m/d H:i:s", strtotime("+30 minutes")));
        $after_30_minutes_in_milisecond = $after_30_minutes*1000 ;

        print("After 30 Minutes ".$after_30_minutes_in_milisecond);
        $token['iat'] = $current_time_in_millisecond;
        $token['exp'] = $after_30_minutes_in_milisecond;
        echo JWT::encode($token, 'secret_server_key');

    }

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

}
?>