<?php

//this class is going to interact with the database
class access
{

    //create needed variable for this class

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
        $this->host=$dbhost;   //assgin the hostname
        $this->user=$dbuser;   //assign the username
        $this->pass=$dbpass;   //assign the password
        $this->dbname=$dbname;  //assgin the db name
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



    public function loginuser($username,$password)  //getting the values
    {
        $sql="select * from user where username='".$username."' and password='".$password."'"; //sql query for get the user's details with username and password
        $result=$this->con->query($sql);  //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the row values from database
            if(!empty($row))      //check whether the row value is null or not
            {
                $returnArray=$row;   // assign the row values to the retrunarray
            }
            return $returnArray;   //return the returnarry to caller
        }
    }

    //Register user
    public function register($username,$password,$email,$firstname,$lastname,$person)
    {
        if($person=="user") {
            $this->createid_user();  //crate auto increment id for the user

            $sql = "INSERT INTO user SET username=?,password=?,email=?,firstname=?,lastname=?,uid=?";  //sql query for insert values to the database
            $statement = $this->con->prepare($sql);   //get the statement executing the sql query

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

            $sql = "INSERT INTO admin SET username=?,password=?,email=?,firstname=?,lastname=?,aid=?";  //sql query for insert values to the database
            $statement = $this->con->prepare($sql);   //get the statement executing the sql query

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
    public function createid_user()
    {

        $sql= "Select * from user ORDER BY id DESC LIMIT 1; ";  //get the last value form the database

        $result=$this->con->query($sql); //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                //echo substr('abcdef', 1, 3);  // bcd

                $id=substr($row["uid"], 3, 6);  //get the integer potion part for  fro example if the database contain a uid USR111111, get the last 6 digit

                $id=$id+1;  //increase the last 6 digit value by one
                $this->uid="USR".$id;  //asign back to id as a USR111112
            }
        }
    }


    public function createid_admin()
    {

        $sql= "Select * from admin ORDER BY id DESC LIMIT 1; ";  //get the last value form the database

        $result=$this->con->query($sql); //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                //echo substr('abcdef', 1, 3);  // bcd

                $id=substr($row["aid"], 2, 6);  //get the integer potion part for  fro example if the database contain a uid USR111111, get the last 6 digit

                $id=$id+1;  //increase the last 6 digit value by one
                $this->uid="AD".$id;  //asign back to id as a USR111112
            }
        }
    }

    public function loginadmin($username,$password)  //getting the values
    {
        $sql="select * from admin where username='".$username."' and password='".$password."'"; //sql query for get the user's details with username and password
        $result=$this->con->query($sql);  //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the row values from database
            if(!empty($row))      //check whether the row value is null or not
            {
                $returnArray=$row;   // assign the row values to the retrunarray
            }
            return $returnArray;   //return the returnarry to caller
        }
    }

    public function usernamecheck($username)
    {
        $sql="select * from user where username='".$username."'"; //sql query for get the user's details with username and password
        $result=$this->con->query($sql);  //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the row values from database
            if(!empty($row))      //check whether the row value is null or not
            {
                $returnArray=$row;   // assign the row values to the retrunarray
            }
            return $returnArray;   //return the returnarry to caller
        }
        else
        {
            $sql="select * from admin where username='".$username."'"; //sql query for get the user's details with username and password
            $result=$this->con->query($sql);  //get the result by executing the sql query

            if ($result !=null && (mysqli_num_rows($result)>=1))  //check the query contain the result or not
            {
                $row=$result->fetch_array(MYSQLI_ASSOC);   //get the row values from database
                if(!empty($row))      //check whether the row value is null or not
                {
                    $returnArray=$row;   // assign the row values to the retrunarray
                }
                return $returnArray;   //return the returnarry to caller
            }
        }
    }
















    public function file_dashboard()
    {
        $returnArray=array();

        $month_trojan=$this->get_file_month_count("Trojan");
        $month_adware=$this->get_file_month_count("Adware");
        $month_normal=$this->get_file_month_count("Normal File");

        $pmonth=array();
        $month=array();
        for($i=0; $i<count($month_adware); $i++)
        {
            $pmonth["month"]=date("Y")."-".($i+1);
            $pmonth["adware"]=$month_adware[$i];
            $pmonth["trojan"]=$month_trojan[$i];
            $pmonth["normal"]=$month_normal[$i];
            array_push($month,$pmonth);
        }
        array_push($returnArray,$month);

        return $returnArray;
    }

    public function get_file_count()
    {
        $trojan_count=0;
        $adware_count=0;
        $normal_count=0;
        $returnArray=array();
        $sql="select * from user_file";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            while($row = $result->fetch_assoc()) {
                $returnArray[]=$row;
            }
        }
        for ($i=0; $i<count($returnArray); $i++)
        {
            if($returnArray[$i]["malware"]=="Trojan")
            {
                $trojan_count++;
            }

            else if($returnArray[$i]["malware"]=="Adware")
            {
                $adware_count++;
            }
            else if($returnArray[$i]["malware"]=="Normal File")
            {
                $normal_count++;
            }
        }

        $count_array=array();
        $count_array["trojan_count"]=$trojan_count;
        $count_array["adware_count"]=$adware_count;
        $count_array["normal_file_count"]=$normal_count;
        return $count_array;
    }

    public function get_file_year_count()
    {

        $returnArray=array();
        $sql = "select COUNT(*) as mal,malware,date from user_file  group by date,malware";

        //$sql = "select COUNT(*) as mal,malware, date from user_file WHERE  group by date,malware";


        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }


        $array=array();
        for($i=0; $i<count($returnArray); $i++)
        {
            $temparray["period"]=$returnArray[$i]["date"];
            $temparray["adware"]=0;
            $temparray["trojan"]=0;
            $temparray["normal"]=0;

            if($returnArray[$i]["malware"]=="Adware")
            {
                $temparray["adware"]=$returnArray[$i]["mal"];
            }

            else if($returnArray[$i]["malware"]=="Trojan")
            {
                $temparray["trojan"]=$returnArray[$i]["mal"];
            }

            else if ($returnArray[$i]["malware"]=="Normal File")
            {
                $temparray["normal"]=$returnArray[$i]["mal"];
            }



            for ($j=$i; $j<count($returnArray); $j++)
            {
                if($returnArray[$i]["date"]==$returnArray[$j]["date"])
                {
                    $i=$j;
                    if($returnArray[$i]["malware"]=="Adware")
                    {
                        $temparray["adware"]=$returnArray[$i]["mal"];
                    }

                    else if($returnArray[$i]["malware"]=="Trojan")
                    {
                        $temparray["trojan"]=$returnArray[$i]["mal"];
                    }

                    else if ($returnArray[$i]["malware"]=="Normal File")
                    {
                        $temparray["normal"]=$returnArray[$i]["mal"];
                    }
                }
            }

            array_push($array,$temparray);
        }
        return $array;

    }
    public function get_file_month_count($malware)
    {

        $count=0;
        $month=array();
        for ($i=0; $i<12; $i++)
        {
            $sql = "SELECT count(*) as malware_count FROM user_file WHERE month(date) = ($i+1) AND malware='$malware'";
            $result = $this->con->query($sql);

            if ($result != null && (mysqli_num_rows($result) >= 1)) {
                while ($row = $result->fetch_assoc()) {
                    $count= $row["malware_count"];
                }
            }
            $month[$i]=$count;
        }

        return $month;
    }


    public function get_all_file()
    {


        $returnArray=array();
        $sql = "SELECT * from user_file ORDER BY id DESC ";
        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }
        return $returnArray;
    }






    public function mail_dashboard()
    {
        $returnArray=array();

        $month_trojan=$this->get_mail_month_count("Trojan");
        $month_adware=$this->get_mail_month_count("Adware");
        $month_normal=$this->get_mail_month_count("Normal File");

        $pmonth=array();
        $month=array();
        for($i=0; $i<count($month_adware); $i++)
        {
            $pmonth["month"]=date("Y")."-".($i+1);
            $pmonth["adware"]=$month_adware[$i];
            $pmonth["trojan"]=$month_trojan[$i];
            $pmonth["normal"]=$month_normal[$i];
            array_push($month,$pmonth);
        }
        array_push($returnArray,$month);

        return $returnArray;
    }



    public function get_mail_month_count($malware)
    {

        $count=0;
        $month=array();
        for ($i=0; $i<12; $i++)
        {
            $sql = "SELECT count(*) as malware_count FROM mail WHERE month(date) = ($i+1) AND malware='$malware'";
            $result = $this->con->query($sql);

            if ($result != null && (mysqli_num_rows($result) >= 1)) {
                while ($row = $result->fetch_assoc()) {
                    $count= $row["malware_count"];
                }
            }
            $month[$i]=$count;
        }

        return $month;
    }


    public function get_mail_count()
    {
        $trojan_count=0;
        $adware_count=0;
        $normal_count=0;
        $returnArray=array();
        $sql="select * from mail";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            while($row = $result->fetch_assoc()) {
                $returnArray[]=$row;
            }
        }
        for ($i=0; $i<count($returnArray); $i++)
        {
            if($returnArray[$i]["malware"]=="Trojan")
            {
                $trojan_count++;
            }

            else if($returnArray[$i]["malware"]=="Adware")
            {
                $adware_count++;
            }
            else if($returnArray[$i]["malware"]=="Normal File")
            {
                $normal_count++;
            }
        }

        $count_array=array();
        $count_array["trojan_count"]=$trojan_count;
        $count_array["adware_count"]=$adware_count;
        $count_array["normal_file_count"]=$normal_count;
        return $count_array;
    }


    public function get_mail_year_count()
    {

        $returnArray=array();
        $sql = "select COUNT(*) as mal,malware,DATE(date) as date from mail  group by DATE(date),malware";

        //$sql = "select COUNT(*) as mal,malware, date from user_file WHERE  group by date,malware";


        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }


        $array=array();
        for($i=0; $i<count($returnArray); $i++)
        {
            $temparray["period"]=$returnArray[$i]["date"];
            $temparray["adware"]=0;
            $temparray["trojan"]=0;
            $temparray["normal"]=0;

            if($returnArray[$i]["malware"]=="Adware")
            {
                $temparray["adware"]=$returnArray[$i]["mal"];
            }

            else if($returnArray[$i]["malware"]=="Trojan")
            {
                $temparray["trojan"]=$returnArray[$i]["mal"];
            }

            else if ($returnArray[$i]["malware"]=="Normal File")
            {
                $temparray["normal"]=$returnArray[$i]["mal"];
            }



            for ($j=$i; $j<count($returnArray); $j++)
            {
                if($returnArray[$i]["date"]==$returnArray[$j]["date"])
                {
                    $i=$j;
                    if($returnArray[$i]["malware"]=="Adware")
                    {
                        $temparray["adware"]=$returnArray[$i]["mal"];
                    }

                    else if($returnArray[$i]["malware"]=="Trojan")
                    {
                        $temparray["trojan"]=$returnArray[$i]["mal"];
                    }

                    else if ($returnArray[$i]["malware"]=="Normal File")
                    {
                        $temparray["normal"]=$returnArray[$i]["mal"];
                    }
                }
            }

            array_push($array,$temparray);
        }
        return $array;

    }



    public function get_all_mail()
    {


        $returnArray=array();
        $sql = "SELECT * from mail ORDER BY id DESC ";
        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }
        return $returnArray;
    }




    public function phone_dashboard()
    {
        return $this->get_phone_month_count();
    }



    public function get_phone_month_count()
    {
        $trojan=array();
        $adware=array();
        $normal=array();
        $returnArray=array();
        for ($i=0; $i<12; $i++) {
            $arr = array();
            $sql = "SELECT details,date FROM user_phone WHERE month(date) = ($i+1)";
            $result = $this->con->query($sql);

            if ($result != null && (mysqli_num_rows($result) >= 1)) {
                while ($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
            }
            //echo $i;
            //print_r($arr);

            $trojan[$i] = 0;
            $adware[$i] = 0;
            $normal[$i] = 0;


            for ($j = 0; $j < count($arr); $j++) {
                //echo $j;
                //print_r($arr[$j]);

                for ($k = 0; $k < count(json_decode($arr[$j]["details"], true)) - 1; $k++) {
                    if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Trojan") {
                        $trojan[$i]++;
                    } else if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Adware") {
                        $adware[$i]++;
                    } else if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Normal File") {
                        $normal[$i]++;
                    }
                }
            }
        }


        //print_r($trojan);
        //print_r($adware);
        //print_r($normal);

        //print_r($returnArray);
        for($i=0; $i<count($trojan); $i++)
        {
            $pmonth=array();
            $pmonth["month"]=date("Y")."-".($i+1);
            $pmonth["adware"]=$adware[$i];
            $pmonth["trojan"]=$trojan[$i];
            $pmonth["normal"]=$normal[$i];
            //print_r($pmonth);
            array_push($returnArray,$pmonth);
        }

        return $returnArray;
    }





    public function get_all_phone()
    {


        $returnArray=array();
        $sql = "SELECT * from user_phone ORDER BY id DESC ";
        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }
        return $returnArray;
    }


    public function get_phone_count()
    {
        $trojan_count=0;
        $adware_count=0;
        $normal_count=0;
        $returnArray=array();
        $sql="select * from user_phone";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            while($row = $result->fetch_assoc()) {
                $returnArray[]=$row;
            }
        }

        for ($i=0; $i<count($returnArray); $i++)
        {
            for($j=0; $j<count(json_decode($returnArray[$i]["details"],true))-1; $j++)
            {
                if (json_decode($returnArray[$i]["details"],true)[$j]["malware"] == "Trojan") {
                    $trojan_count++;
                } else if (json_decode($returnArray[$i]["details"],true)[$j]["malware"] == "Adware") {
                    $adware_count++;
                } else if (json_decode($returnArray[$i]["details"],true)[$j]["malware"] == "Normal File") {
                    $normal_count++;
                }
            }
        }

        $count_array=array();
        $count_array["trojan_count"]=$trojan_count;
        $count_array["adware_count"]=$adware_count;
        $count_array["normal_file_count"]=$normal_count;
        return $count_array;
    }


    public function get_phone_year_count()
    {

        $returnArray=array();
        $sql = "select COUNT(*) as mal,details,DATE(date) as date from user_phone  group by DATE(date)";

        //$sql = "select COUNT(*) as mal,malware, date from user_file WHERE  group by date,malware";


        $year=array();

        $count=0;
        $pyear=array();
        $result = $this->con->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {

                $returnArray[]=$row;
            }
        }


        for($i=0; $i<count($returnArray); $i++)
        {
            $date=$returnArray[$i]["date"];
            $returnArrayValue=array();
            $sql = "select details,DATE(date) as date from user_phone  where DATE(date)='$date'";

            //$sql = "select COUNT(*) as mal,malware, date from user_file WHERE  group by date,malware";


            $result=mysqli_query($this->con,$sql);
            $no_of_rows=mysqli_num_rows($result);
            if ($no_of_rows > 0)
            {
                while($row=mysqli_fetch_assoc($result))
                {
                    $returnArrayValue[]=$row;
                }
            }
            $pyear["period"] = $returnArray[$i]["date"];
            $pyear["adware"] = 0;
            $pyear["trojan"] = 0;
            $pyear["normal"] = 0;

            for($j=0; $j<count($returnArrayValue); $j++) {
                for ($k = 0; $k < count(json_decode($returnArrayValue[$j]["details"], true)) - 1; $k++) {
                    if (json_decode($returnArrayValue[$j]["details"], true)[$k]["malware"] == "Trojan") {
                        $pyear["trojan"]++;
                    } else if (json_decode($returnArrayValue[$j]["details"], true)[$k]["malware"] == "Adware") {
                        $pyear["adware"]++;
                    } else if (json_decode($returnArrayValue[$j]["details"], true)[$k]["malware"] == "Normal File") {
                        $pyear["normal"]++;
                    }
                }
            }

            array_push($year, $pyear);
        }


        return $year;

    }






    public function get_all_users()
    {


        $returnArray=array();
        $sql = "SELECT * from user ORDER BY id DESC ";
        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }
        return $returnArray;
    }


    public function get_all_admin()
    {


        $returnArray=array();
        $sql = "SELECT * from admin ORDER BY id DESC ";
        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }
        return $returnArray;
    }















    public function file_dashboard_user($user)
    {
        $returnArray=array();

        $month_trojan=$this->get_file_month_count_user("Trojan",$user);
        $month_adware=$this->get_file_month_count_user("Adware",$user);
        $month_normal=$this->get_file_month_count_user("Normal File",$user);

        $pmonth=array();
        $month=array();
        for($i=0; $i<count($month_adware); $i++)
        {
            $pmonth["month"]=date("Y")."-".($i+1);
            $pmonth["adware"]=$month_adware[$i];
            $pmonth["trojan"]=$month_trojan[$i];
            $pmonth["normal"]=$month_normal[$i];
            array_push($month,$pmonth);
        }
        array_push($returnArray,$month);

        return $returnArray;
    }

    public function get_file_count_user($user)
    {
        $trojan_count=0;
        $adware_count=0;
        $normal_count=0;
        $returnArray=array();
        $sql="select * from user_file where user_id='$user'";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            while($row = $result->fetch_assoc()) {
                $returnArray[]=$row;
            }
        }
        for ($i=0; $i<count($returnArray); $i++)
        {
            if($returnArray[$i]["malware"]=="Trojan")
            {
                $trojan_count++;
            }

            else if($returnArray[$i]["malware"]=="Adware")
            {
                $adware_count++;
            }
            else if($returnArray[$i]["malware"]=="Normal File")
            {
                $normal_count++;
            }
        }

        $count_array=array();
        $count_array["trojan_count"]=$trojan_count;
        $count_array["adware_count"]=$adware_count;
        $count_array["normal_file_count"]=$normal_count;
        return $count_array;
    }

    public function get_file_year_count_user($user)
    {

        $returnArray=array();
        $sql = "select COUNT(*) as mal,malware,date from user_file where user_id='$user'  group by date,malware ";

        //$sql = "select COUNT(*) as mal,malware, date from user_file WHERE  group by date,malware";


        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }


        $array=array();
        for($i=0; $i<count($returnArray); $i++)
        {
            $temparray["period"]=$returnArray[$i]["date"];
            $temparray["adware"]=0;
            $temparray["trojan"]=0;
            $temparray["normal"]=0;

            if($returnArray[$i]["malware"]=="Adware")
            {
                $temparray["adware"]=$returnArray[$i]["mal"];
            }

            else if($returnArray[$i]["malware"]=="Trojan")
            {
                $temparray["trojan"]=$returnArray[$i]["mal"];
            }

            else if ($returnArray[$i]["malware"]=="Normal File")
            {
                $temparray["normal"]=$returnArray[$i]["mal"];
            }



            for ($j=$i; $j<count($returnArray); $j++)
            {
                if($returnArray[$i]["date"]==$returnArray[$j]["date"])
                {
                    $i=$j;
                    if($returnArray[$i]["malware"]=="Adware")
                    {
                        $temparray["adware"]=$returnArray[$i]["mal"];
                    }

                    else if($returnArray[$i]["malware"]=="Trojan")
                    {
                        $temparray["trojan"]=$returnArray[$i]["mal"];
                    }

                    else if ($returnArray[$i]["malware"]=="Normal File")
                    {
                        $temparray["normal"]=$returnArray[$i]["mal"];
                    }
                }
            }

            array_push($array,$temparray);
        }
        return $array;

    }

    public function get_file_month_count_user($malware,$user)
    {

        $count=0;
        $month=array();
        for ($i=0; $i<12; $i++)
        {
            $sql = "SELECT count(*) as malware_count FROM user_file WHERE month(date) = ($i+1) AND malware='$malware' AND user_id='$user'";
            $result = $this->con->query($sql);

            if ($result != null && (mysqli_num_rows($result) >= 1)) {
                while ($row = $result->fetch_assoc()) {
                    $count= $row["malware_count"];
                }
            }
            $month[$i]=$count;
        }

        return $month;
    }


    public function get_all_file_user($user)
    {


        $returnArray=array();
        $sql = "SELECT * from user_file where user_id='$user' ORDER BY id DESC ";
        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }
        return $returnArray;
    }







    public function phone_dashboard_user($user)
    {
        return $this->get_phone_month_count_user($user);
    }



    public function get_phone_month_count_user($user)
    {
        $trojan=array();
        $adware=array();
        $normal=array();
        $returnArray=array();
        for ($i=0; $i<12; $i++) {
            $arr = array();
            $sql = "SELECT details,date FROM user_phone WHERE month(date) = ($i+1) AND user_id='$user'";
            $result = $this->con->query($sql);

            if ($result != null && (mysqli_num_rows($result) >= 1)) {
                while ($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
            }
            //echo $i;
            //print_r($arr);

            $trojan[$i] = 0;
            $adware[$i] = 0;
            $normal[$i] = 0;


            for ($j = 0; $j < count($arr); $j++) {
                //echo $j;
                //print_r($arr[$j]);

                for ($k = 0; $k < count(json_decode($arr[$j]["details"], true)) - 1; $k++) {
                    if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Trojan") {
                        $trojan[$i]++;
                    } else if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Adware") {
                        $adware[$i]++;
                    } else if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Normal File") {
                        $normal[$i]++;
                    }
                }
            }
        }


        //print_r($trojan);
        //print_r($adware);
        //print_r($normal);

        //print_r($returnArray);
        for($i=0; $i<count($trojan); $i++)
        {
            $pmonth=array();
            $pmonth["month"]=date("Y")."-".($i+1);
            $pmonth["adware"]=$adware[$i];
            $pmonth["trojan"]=$trojan[$i];
            $pmonth["normal"]=$normal[$i];
            //print_r($pmonth);
            array_push($returnArray,$pmonth);
        }

        return $returnArray;
    }





    public function get_all_phone_user($user)
    {


        $returnArray=array();
        $sql = "SELECT * from user_phone where user_id='$user' ORDER BY id DESC ";
        $result = $this->con->query($sql);

        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {
                $returnArray[]= $row;
            }
        }
        return $returnArray;
    }


    public function get_phone_count_user($user)
    {
        $trojan_count=0;
        $adware_count=0;
        $normal_count=0;
        $returnArray=array();
        $sql="select * from user_phone where user_id='$user' ";
        $result=$this->con->query($sql);

        if ($result !=null && (mysqli_num_rows($result)>=1))
        {
            while($row = $result->fetch_assoc()) {
                $returnArray[]=$row;
            }
        }

        for ($i=0; $i<count($returnArray); $i++)
        {
            for($j=0; $j<count(json_decode($returnArray[$i]["details"],true))-1; $j++)
            {
                if (json_decode($returnArray[$i]["details"],true)[$j]["malware"] == "Trojan") {
                    $trojan_count++;
                } else if (json_decode($returnArray[$i]["details"],true)[$j]["malware"] == "Adware") {
                    $adware_count++;
                } else if (json_decode($returnArray[$i]["details"],true)[$j]["malware"] == "Normal File") {
                    $normal_count++;
                }
            }
        }

        $count_array=array();
        $count_array["trojan_count"]=$trojan_count;
        $count_array["adware_count"]=$adware_count;
        $count_array["normal_file_count"]=$normal_count;
        return $count_array;
    }


    public function get_phone_year_count_user($user)
    {

        $returnArray=array();
        $sql = "select COUNT(*) as mal,details,DATE(date) as date from user_phone where user_id='$user'  group by DATE(date)";

        //$sql = "select COUNT(*) as mal,malware, date from user_file WHERE  group by date,malware";


        $year=array();

        $count=0;
        $pyear=array();
        $result = $this->con->query($sql);
        if ($result != null && (mysqli_num_rows($result) >= 1)) {
            while ($row = $result->fetch_assoc()) {

                $returnArray[]=$row;
            }
        }


        for($i=0; $i<count($returnArray); $i++)
        {
            $date=$returnArray[$i]["date"];
            $returnArrayValue=array();
            $sql = "select details,DATE(date) as date from user_phone  where DATE(date)='$date' AND user_id='$user'";

            //$sql = "select COUNT(*) as mal,malware, date from user_file WHERE  group by date,malware";


            $result=mysqli_query($this->con,$sql);
            $no_of_rows=mysqli_num_rows($result);
            if ($no_of_rows > 0)
            {
                while($row=mysqli_fetch_assoc($result))
                {
                    $returnArrayValue[]=$row;
                }
            }
            $pyear["period"] = $returnArray[$i]["date"];
            $pyear["adware"] = 0;
            $pyear["trojan"] = 0;
            $pyear["normal"] = 0;

            for($j=0; $j<count($returnArrayValue); $j++) {
                for ($k = 0; $k < count(json_decode($returnArrayValue[$j]["details"], true)) - 1; $k++) {
                    if (json_decode($returnArrayValue[$j]["details"], true)[$k]["malware"] == "Trojan") {
                        $pyear["trojan"]++;
                    } else if (json_decode($returnArrayValue[$j]["details"], true)[$k]["malware"] == "Adware") {
                        $pyear["adware"]++;
                    } else if (json_decode($returnArrayValue[$j]["details"], true)[$k]["malware"] == "Normal File") {
                        $pyear["normal"]++;
                    }
                }
            }

            array_push($year, $pyear);
        }


        return $year;

    }





    public function get_phone_critical_month_count()
    {
        $trojan=array();
        $adware=array();
        $normal=array();
        $returnArray=array();
        for ($i=0; $i<12; $i++) {
            $arr = array();
            $sql = "SELECT details,date FROM user_phone WHERE month(date) = ($i+1)";
            $result = $this->con->query($sql);

            if ($result != null && (mysqli_num_rows($result) >= 1)) {
                while ($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
            }
            //echo $i;
            //print_r($arr);

            $trojan[$i] = 0;
            $adware[$i] = 0;
            $normal[$i] = 0;


            for ($j = 0; $j < count($arr); $j++) {
                //echo $j;
                //print_r($arr[$j]);

                for ($k = 0; $k < count(json_decode($arr[$j]["details"], true)) - 1; $k++) {
                    if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Trojan" && json_decode($arr[$j]["details"], true)[$k]["critical"] >=40 ) {
                        $trojan[$i]++;
                    } else if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Adware" && json_decode($arr[$j]["details"], true)[$k]["critical"] >=40 ) {
                        $adware[$i]++;
                    } else if (json_decode($arr[$j]["details"], true)[$k]["malware"] == "Normal File" && json_decode($arr[$j]["details"], true)[$k]["critical"] >=40 ) {
                        $normal[$i]++;
                    }
                }
            }
        }


        //print_r($trojan);
        //print_r($adware);
        //print_r($normal);

        //print_r($returnArray);
        for($i=0; $i<count($trojan); $i++)
        {
            $pmonth=array();
            $pmonth["month"]=date("Y")."-".($i+1);
            $pmonth["adware"]=$adware[$i];
            $pmonth["trojan"]=$trojan[$i];
            $pmonth["normal"]=$normal[$i];
            //print_r($pmonth);
            array_push($returnArray,$pmonth);
        }

        return $returnArray;
    }




    public function mail_dashboard_critical()
    {
        $returnArray=array();

        $month_trojan=$this->get_mail_month_count_critical("Trojan");
        $month_adware=$this->get_mail_month_count_critical("Adware");
        $month_normal=$this->get_mail_month_count_critical("Normal File");

        $pmonth=array();
        $month=array();
        for($i=0; $i<count($month_adware); $i++)
        {
            $pmonth["month"]=date("Y")."-".($i+1);
            $pmonth["adware"]=$month_adware[$i];
            $pmonth["trojan"]=$month_trojan[$i];
            $pmonth["normal"]=$month_normal[$i];
            array_push($month,$pmonth);
        }
        array_push($returnArray,$month);

        return $returnArray;
    }



    public function get_mail_month_count_critical($malware)
    {

        $count=0;
        $month=array();
        for ($i=0; $i<12; $i++)
        {
            $sql = "SELECT count(*) as malware_count FROM mail WHERE month(date) = ($i+1) AND malware='$malware' AND critical>=40";
            $result = $this->con->query($sql);

            if ($result != null && (mysqli_num_rows($result) >= 1)) {
                while ($row = $result->fetch_assoc()) {
                    $count= $row["malware_count"];
                }
            }
            $month[$i]=$count;
        }

        return $month;
    }



    public function file_dashboard_critical()
    {
        $returnArray=array();

        $month_trojan=$this->get_file_month_count_critical("Trojan");
        $month_adware=$this->get_file_month_count_critical("Adware");
        $month_normal=$this->get_file_month_count_critical("Normal File");

        $pmonth=array();
        $month=array();
        for($i=0; $i<count($month_adware); $i++)
        {
            $pmonth["month"]=date("Y")."-".($i+1);
            $pmonth["adware"]=$month_adware[$i];
            $pmonth["trojan"]=$month_trojan[$i];
            $pmonth["normal"]=$month_normal[$i];
            array_push($month,$pmonth);
        }
        array_push($returnArray,$month);

        return $returnArray;
    }
    public function get_file_month_count_critical($malware)
    {

        $count=0;
        $month=array();
        for ($i=0; $i<12; $i++)
        {
            $sql = "SELECT count(*) as malware_count FROM user_file WHERE month(date) = ($i+1) AND malware='$malware' AND critical>40";
            $result = $this->con->query($sql);

            if ($result != null && (mysqli_num_rows($result) >= 1)) {
                while ($row = $result->fetch_assoc()) {
                    $count= $row["malware_count"];
                }
            }
            $month[$i]=$count;
        }

        return $month;
    }







}
?>