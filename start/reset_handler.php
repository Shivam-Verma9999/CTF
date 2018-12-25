<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 2/23/2018
 * Time: 4:01 AM
 */
include("../include/connection.php");
include("../include/functions.php");
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['forgot_password']) && $_POST['forgot_password'] == "true" && $_POST['email'] != null){
    $email = test_input($_POST['email']);
    //Check for valid email
    $conn = create_connection();
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_rows = $result->num_rows;
    $conn->close();
    if($num_rows == 1){
        $row = $result->fetch_assoc();
        $key = generate_reset_key($row['email']);
        //Check for any existing reset request AND delete it
        $conn = create_connection();
        $query = "DELETE FROM password_reset WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $row['username']);
        $stmt->execute();
        $conn->close();
        //Mail the new reset link
        if(mail_reset_link($row['name'], $row['email'], $key)){
            //Insert the reset key into database
            $conn = create_connection();
            $query = "INSERT INTO (username, reset_key) password_reset VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $row['username'], $key);
            $stmt->execute();
            $conn->close();
            echo("Reset link has been successfully sent to {$email}");
        }else{
            echo("Unexpected Error occurred while sending mail to {$email}");
        }
    }else{
        echo("No account exists with this email");
    }
    die();
}elseif($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['password'], $_POST['conf_password'], $_POST['username'], $_POST['key'])){
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];
    $username = test_input($_POST['username']);
    $key = test_input($_POST['key']);

    if($password == $conf_password) {
        //Check for valid key and username
        $conn = create_connection();
        $query = "SELECT * FROM password_reset WHERE username = ? AND reset_key = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $key);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_rows = $result->num_rows;
        $row = $result->fetch_assoc();
        $conn->close();
        if ($num_rows == 1) {
            //Change the password and redirect to signin page
            $password = md5($password);
            $conn = create_connection();
            $query1 = "UPDATE users SET password = ? WHERE username = ?";
            $query2 = "UPDATE registration_temp SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($query1);
            $stmt->bind_param("ss", $password, $row['username']);
            $stmt->execute();
            $conn->close();
            $conn = create_connection();
            $stmt = $conn->prepare($query2);
            $stmt->bind_param("ss", $password, $row['username']);
            $stmt->execute();
            $conn->close();
            //Delete data from password_reset
            $conn = create_connection();
            $query = "DELETE FROM password_reset WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $row['username']);
            $stmt->execute();
            $conn->close();
            redirect("./");
        } else {
            redirect("../");
        }
    }else{
        redirect("../");
    }
}else{
    redirect("../");
}