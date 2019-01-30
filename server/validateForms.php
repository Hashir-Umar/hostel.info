<?php
    session_start();
    include_once("functions.php");
    require_once "database_connection.php";
    
    if(isset($_REQUEST['login'])){
       
        $email = mysqli_real_escape_string($conn, $_REQUEST['email']);
        $pass = mysqli_real_escape_string($conn, $_REQUEST['password']);

        $regex_email = '/^[A-Za-z0-9]+\.?[A-Za-z0-9]+\@[a-z0-9]+\.[a-z]{2,4}(\.[a-z]{2,4})?$/';          
        $regex_pass = "/^.{6,}$/";

        if(!preg_match($regex_email, $email))
        {
            $_SESSION['error_msg'] = "*Invalid Email";
            header('Location: ../pages/login.php');
            die();
        }

        if(!preg_match($regex_pass, $pass))
        {
            $_SESSION['error_msg'] = "*Invalid password";
            header('Location: ../pages/login.php');
            die();
        }

        $sql = "SELECT  * FROM `users` WHERE user_email = '$email' AND  user_password = '$pass'";
        $result = $conn->query($sql);

        if($result->num_rows > 0)
        {   
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_email'] = $row['user_email'];
            $_SESSION['user_account_type'] = $row['user_account_type'];
            
            if($row['user_account_type'] == 3)
            {
                header('Location: ../pages/admin-panel.php'); 
            }
            else
                header('Location: ../index.php'); 

            if(!empty($_REQUEST['remember'])) {
                header("Location: setLoginCookies.php?email=".$email."&password=".$pass);
            } else {
                header("Location: setLoginCookies.php?email=&password=");
            }
               
        }
        else
        {
            $_SESSION['error_msg'] = "*Email and Password does not match";
            header('Location: ../pages/login.php');
            die();
        }

    }
    else if(isset($_POST["uploadHostel"]))
    {  
        $hostel_name = $_POST["hostel_name"];
        $hostel_city = $_POST["hostel_city"];
        $hostel_address = $_POST["hostel_address"];
        $hostel_rooms = $_POST["hostel_rooms"];
        $hostel_extras = $_POST["hostel_extras"];
        $hostel_image_name = array();
        $hostel_image_temp_name = array();

        foreach($_FILES["user_file"]["tmp_name"] as $key=>$tmp_name)
        {
            $file_name=$_FILES["user_file"]["name"][$key];
            $file_tmp=$_FILES["user_file"]["tmp_name"][$key];
           
            if (!preg_match('/image/', $_FILES['user_file']['type'][$key]))
            {
                $_SESSION['error_msg'] = "Non-image files are not allowed!";
                header("Location: ../pages/hostel-admin.php");
                die();
            }

            array_push($hostel_image_name, basename($file_name));
            array_push($hostel_image_temp_name, $file_tmp);
        }
        
        $hostel_name_regex = '/^(?=[a-z]{2})(?=.{4,26})(?=[^.]*\.?[^.]*$)(?=[^_]*_?[^_]*$)[\w.]+$/iD';
        $hostel_city_regex = '/Lahore|Islamabad|Karachi|Faisalabad|Peshawar|Quetta/';
        $hostel_address_regex = '/^[a-zA-Z]([a-zA-Z-]+\s)+\d{1,4}$/';
        
        if($hostel_name == ""
        || $hostel_city == ""
        || $hostel_address == ""
        || $hostel_rooms == ""
        || $hostel_image_name == "") {
            $_SESSION['error_msg'] = "You can not leave any feild empty";
            header("Location: ../pages/add-hostel.php");
            die();
        }

        if (!preg_match($hostel_name_regex,  $hostel_name))  {
            $_SESSION['error_msg'] = "Hostel name not valid";
            header("Location: ../pages/hostel-admin.php");
        }
        else if (!preg_match($hostel_city_regex,  $hostel_city))  {
            $_SESSION['error_msg'] = "City name not valid";
            header("Location: ../pages/hostel-admin.php");
        }
        else if (!preg_match($hostel_address_regex,  $hostel_address))  {
            $_SESSION['error_msg'] = "Address not valid";
            header("Location: ../pages/hostel-admin.php");
        }
        else {
            $hostel_owner = $_SESSION['user_id'];
            $uploaddir = 'src/hostel_images/'.$hostel_image_name[0];

            global $conn;
            $sql = "INSERT INTO `pending_hostels`(`hostel_name`, `hostel_city`, `hostel_address`, `hostel_rooms`, `hostel_extras`, `hostel_owner`, `hostel_img`) VALUES ('".$hostel_name."', '".$hostel_city."', '".$hostel_address."', '".$hostel_rooms."', '".$hostel_extras."', '".$hostel_owner."', '".$uploaddir."');";
            $result = mysqli_query($conn,$sql);
            if(!$result) {
                die("Error description: " . mysqli_error($conn));
            }

            //// updating the image name with the hostel id/////////////
            
            $hostel_id = $conn->insert_id;                                            //get the recently added hostel id
            $uploaddir = 'src/hostel_images/'.$hostel_id."_".$hostel_image_name[0];
            $sql = "UPDATE pending_hostels SET hostel_img = '$uploaddir' WHERE hostel_id = '$hostel_id'";
            if(!mysqli_query($conn,$sql)) {
                die("Error description: " . mysqli_error($conn));
            }

            ////////////////////////////////////////////

            $array_size = count($hostel_image_name);
            for($i = 0; $i < $array_size; $i++)
            {
                $uploaddir = 'src/hostel_images/'.$hostel_id.'_'.$hostel_image_name[$i];
                
                $sql = "INSERT INTO `hostels_images`(`pending_hostel_id`, `hostel_pic`) VALUES ('".$hostel_id."', '".$uploaddir."');";
                $result = mysqli_query($conn,$sql);
                if(!$result) {
                    die("Error description: " . mysqli_error($conn));
                }

                if (move_uploaded_file($hostel_image_temp_name[$i], '../'.$uploaddir)) {
                    $flag = 1;
                } else {
                    $_SESSION['error_msg'] = "Error occured while uploading image.";
                    header("Location: ../pages/hostel-admin.php");
                    die();
                }
            }

            if($flag == 1)
            {
                $_SESSION['error_msg'] = "Your hostel has been successfully sent for review by our team. You will be notified once it gets reviewed.";
                header("Location: ../pages/hostel-admin.php");
            }
        }
		
    }
	else if(isset($_POST['submit'])){

        $email_regex = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        $pass_regex =  "/^.{6,}$/";
        
        $fname = $_POST['first_name'];
        $lname = $_POST['last_name'];
        $phone_no = $_POST['phone'];
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $Gender = $_POST['Gender'];
        $cnfrm_pass = mysqli_real_escape_string($conn, $_POST['confirm_password']);
        $user_account_type = 2;
        
        if(!isset($_POST['user_account_type']))
            $user_account_type = 1;
        
        if(!preg_match($email_regex,  $email))
        {
            $_SESSION['error_msg'] = "Email is invalid.";
            header("Location: ../pages/signup.php");
            die();
        }
        if(!preg_match($pass_regex,  $password))
        {
            $_SESSION['error_msg'] = "Password length should be 6 or greater.";
            header("Location: ../pages/signup.php");
            die();
        }
        
        if($cnfrm_pass != $password)
        {
            $_SESSION['error_msg'] = "Password does not match.";
            header("Location: ../pages/signup.php");
            die();
        }

        $sql1 = "SELECT  * FROM `users` WHERE user_email = '$email'";
        $result1 = $conn->query($sql1);

        $sql2 = "SELECT  * FROM `pending_users` WHERE user_email = '$email'";
        $result2 = $conn->query($sql2);

        if($result1->num_rows > 0 || $result2->num_rows > 0)
        {
            $_SESSION['error_msg'] = "Entered email is already present.";
            header("Location: ../pages/signup.php");
        }
        else
        {   
            if($user_account_type == 1) {
                $phone_no = "";
            }
            insertData($conn,$fname,$lname,$Gender,$email, $password,$phone_no,$user_account_type);
        }
    }
    else if(isset($_GET['checkEmail']))
    {
        $email_regex = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
        $email = $_GET['checkEmail'];

        if(preg_match($email_regex,  $email))
        {
            $sql1 = "SELECT  * FROM `users` WHERE user_email = '$email'";
            $result1 = $conn->query($sql1);
    
            $sql2 = "SELECT  * FROM `pending_users` WHERE user_email = '$email'";
            $result2 = $conn->query($sql2);
    
            if($result1->num_rows > 0 || $result2->num_rows > 0) {
                echo "1";
            }
            else {
                echo "0";
            }
        }
        else
            echo "1";
    }

?>