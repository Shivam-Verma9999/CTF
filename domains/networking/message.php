<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/9/2018
 * Time: 8:38 AM
 */
session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "networking_message";

if (isset($_SESSION['username'])) {
//Validate the answer and submit
    if (isset($_POST['submit'], $_POST['key']) && $_POST['key'] != null) {
        $key = trim($_POST['key']);
        $conn = create_connection();
        $query = "SELECT * FROM question WHERE id = ? AND solution = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $_SESSION['question_id'], $key);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
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
            $query = "UPDATE leaderboard SET total_score = total_score + " . $marks . " WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $conn->close();
            redirect("../");
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
                Sir, When I was tracking some hydra activities, I got a computer program which was stored in safe
                storage.
            </div>
            <div class="message sent">
                <p class="tony_name">Stark:</p>
                Can you provide link to the program.
            </div>

            <div class=" message recieved">
                <p class="jarvis_name">Jarvis:</p>
                Sure sir, here is the link <a href="./RedSkullTalking.jar" target="_blank">RedSkullTalking.jar</a>
            </div>
            <div class=" message sent">
                <p class="tony_name">Stark:</p>
                Jarvis, it is trying to communicate with someone.
            </div>
            <div class=" message recieved">
                <p class="jarvis_name">Jarvis:</p>
                Or it may be sending some UDP messages to all hydra members.
            </div>
            <div class="message sent">
                <p class="tony_name">Stark:</p>
                I will figure it out.
            </div>
        </div>
    </div>

    <div class="form">
        <form action="./message.php" method="post">
            <div class="input"><input type="text" name="key" required></div>
            <div class="submit"><input type="submit" value="Proceed"></div>
        </form>
    </div>

    </body>

    </html>
<?php } else {
    redirect("../");
} ?>