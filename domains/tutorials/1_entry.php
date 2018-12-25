<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/8/2018
 * Time: 1:51 AM
 */
session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "tutorial001";

if(isset($_SESSION['username']) && $_SESSION['tutorial_level'] == 0){
    //Validate the answer and submit
    if(isset($_POST['submit'], $_POST['key']) && $_POST['key'] != null){
        $key = trim($_POST['key']);
        $conn = create_connection();
        $query = "SELECT * FROM question WHERE id = ? AND solution = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $_SESSION['question_id'], $key);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            $conn->close();
            //Insert data into solved table
            $conn = create_connection();
            $query = "INSERT INTO solved (username, question_id) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $_SESSION['username'], $_SESSION['question_id']);
            $stmt->execute();
            $conn->close();
            //Update leaderboard
            $marks = (int)$row['marks'];
            $conn = create_connection();
            $query = "INSERT INTO leaderboard (username, total_score) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $_SESSION['username'], $marks);
            $stmt->execute();
            $conn->close();
            //Promote the tutorial level
            $conn = create_connection();
            $query = "UPDATE users SET tutorial_level = 1 WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $conn->close();
            $_SESSION['tutorial_level'] = 1;
            redirect("./2_script.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/conversation.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <title>Document</title>
</head>

<body>
<div class="main">
    <div class="message_pane">
        <div class=" message recieved">
            <p class="jarvis_name">Jarvis:</p>
            Good Morning sir!, Its Saturday, <?php echo(date("d M, Y"));?>. <br> Outside is at 34 &#8457;.
            Precipitation is 97% and humidity 93%. Wind speed is 14 mph and a good day for hill side driving. But I
            think you prefer flying in new iron suit Mak 43. Minimum safe height for flying is 523 meters and average
            height of passenger planes is 10,000 meters. Today's new heading .....
        </div>
        <div class="message sent">
            <p class="tony_name">Stark:</p>Wait Jarvis!, I didn't program you for weather forecasting.
        </div>

        <div class=" message recieved">
            <p class="jarvis_name">Jarvis:</p>
            Sure sir, but I have prepared news headlines for today. Would you .....
        </div>
        <div class=" message sent">
            <p class="tony_name">Stark:</p>
            I don't have any interest in parliament activities.
        </div>
        <div class=" message sent">
            Jarvis, what is the status of locating the Loki's sceptre.
        </div>
        <div class=" message recieved">
            <p class="jarvis_name">Jarvis:</p>
            Our satellites are unable to detect the energy signature that matched to Tesseract cube.
            May be Dr. Banner can get you some help.
        </div>
        <div class="message sent">
            <p class="tony_name">Stark:</p>
            Can you get inside Hydra network.
        </div>

        <div class=" message recieved">
            <p class="jarvis_name">Jarvis:</p>
            I tried that but could not crack the pass key.
        </div>
        <div class=" message sent">
            <p class="tony_name">Stark:</p>
            What did you try for that ??
        </div>
        <div class=" message recieved">
            <p class="jarvis_name">Jarvis:</p>
            Sir, I tried some words hydra would definitely like. Words like Red Skull, Super Soldier, nightmare,
            Captain America ....
        </div>
        <div class=" message sent">
            <p class="tony_name">Stark:</p>
            I don't hope Hydra like Captain. May be, the key is somewhere inside its source. Let me try.
        </div>
        <div class=" message recieved">
            <p class="jarvis_name">Jarvis:</p>
            Sure sir.
        </div>
    </div>
</div>

<div class="form">
    <form action="./1_entry.php" method="post">
        <div class="input"><input type="text" name="key" required></div>
        <div class="submit"><input type="submit" value="Proceed"></div>
    </form>
</div>

</body>

</html>

<? }else{ redirect("../"); } ?>