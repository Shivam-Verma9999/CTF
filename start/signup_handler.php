<?php
include("../include/connection.php");
include("../include/functions.php");
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 2/20/2018
 * Time: 9:12 PM
 */
//Handle validate username request
if(isset($_GET['subscription_check'], $_GET['username']) && $_GET['subscription_check'] != null && $_GET['username'] != null){
    $username = test_input($_GET['username']);
    $conn = create_connection();
    $query = "SELECT * FROM registration_temp WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1){
        echo("nope");
    }else{
        echo("ok");
    }
    $conn->close();
    die();
}

//Handle validate email request
if(isset($_GET['subscription_check'], $_GET['email']) && $_GET['subscription_check'] != null && $_GET['email'] != null){
    $username = test_input($_GET['email']);
    $conn = create_connection();
    $query = "SELECT * FROM registration_temp WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1){
        echo("nope");
    }else{
        echo("ok");
    }
    $conn->close();
    die();
}

//Handle Signup request
$message = "";
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['name'], $_POST['username'], $_POST['email'], $_POST['mobile'], $_POST['password'], $_POST['college'])){
    $name = test_input($_POST['name']);
    $username = test_input($_POST['username']);
    $email = strtolower(test_input($_POST['email']));
    $mobile = test_input($_POST['mobile']);
    $password = $_POST['password'];
    $college = test_input($_POST['college']);
    //Check for existing user
    $conn = create_connection();
    $query = "SELECT * FROM registration_temp WHERE email = ? AND confirmed = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_rows = $stmt->num_rows;
    $conn->close();
    if($num_rows == 1){
        $message = "Email address has been already registered. You can reset your password from Forgot password link";
        echo($message);
        die();
    }else {
        //Populate the database with new entry
        $password = md5($password);
        $conn = create_connection();
        $query = "INSERT IGNORE INTO registration_temp (username, name, email, mobile, college, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssiss', $username, $name, $email, $mobile, $college, $password);
        $stmt->execute();
        $conn->close();
        $conf_code = generate_confirmation_key($email);
        if(mail_confirmation_code($name, $email, $conf_code)) {
            $message = "Confirmation mail has been successfully sent to {$email}. Check your inbox or spam folder.";
            echo($message);
        }else{
            echo("Error in sending mail. Please try again.");
        }
    }
}else{redirect("../");}
?>