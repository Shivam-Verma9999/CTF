<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/8/2018
 * Time: 2:04 AM
 */
session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "tutorial002";

if (isset($_SESSION['username']) && $_SESSION['tutorial_level'] == 1) {
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
            //Promote the tutorial level
            $conn = create_connection();
            $query = "UPDATE users SET tutorial_level = 2 WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $conn->close();
            $_SESSION['tutorial_level'] = 2;
            redirect("./3_inject.php");
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
            <div class="message sent">
                <p class="jarvis_name">Jarvis:</p>
                Sir, I think that won't be enough. The network is asking for some sort of key combination.
            </div>

            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                Hydra is so curious about its security but was never secure. I even don't remember how many times
                we have breached through it.
            </div>
            <div class=" message sent">
                <p class="jarvis_name">Jarvis:</p>
                My curiosity is about 'What will Hydra be doing with Loki's sceptre.
            </div>
            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                We will figure out that too. While iterating through code, I saw some random script written. Find
                that execute. Most probably it should work.
            </div>
            <div class="message sent">
                <p class="jarvis_name">Jarvis:</p>
                Ok Sir. On my way.....
            </div>
        </div>
    </div>
    <div class="form">
        <form action="./2_script.php" method="post">
            <div class="input"><input type="text" name="key" required></div>
            <div class="submit"><input type="submit" value="Proceed"></div>
        </form>
    </div>

    </body>
    <script src="knockknock.js"></script>
    </html>

<?php }else{redirect("../");} ?>
