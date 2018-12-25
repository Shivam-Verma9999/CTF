<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/8/2018
 * Time: 12:06 PM
 */

session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "tutorial004";

if (isset($_SESSION['username']) && $_SESSION['tutorial_level'] == 3) {
//Validate the answer and submit
    if (isset($_POST['submit'], $_POST['date']) && trim($_POST['date']) == "2005-04-23") {
        //$date = date("Y-m-d", strtotime($_POST['date']));
        $date = trim($_POST['date']);
        $conn = create_connection();
        $query = "SELECT * FROM question WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $_SESSION['question_id']);
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
            $query = "UPDATE users SET tutorial_level = 4 WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $conn->close();
            $_SESSION['tutorial_level'] = 4;
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
                Sir, I got some activities on a site. <a href="http://www.siteofthehydra.com" target="_blank">www.siteofthehydra.com</a>
            </div>
            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                As per what captain told me, there were two Hydra scientists that were involved in Tessaract cube
                projects.
                I do not clearly remember their names. One of them was Tucker who is in our custody and other one
                something
                like ivan.
            </div>
            <div class=" message sent">
                <p class="jarvis_name">Jarvis:</p>
                Sir, there are 14 ivans in Hydra scientists. Do you remember anything else.
            </div>
            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                That scientist created a website for its communications. Recently you got a website, go to
                <a href="http://www.yougetsignal.com" target="_blank">www.yougetsignal.com</a> and do the reverse ip domain check. That site
                must be
                hosted on same web server.
            </div>
            <div class="message sent">
                <p class="jarvis_name">Jarvis:</p>
                I got that website having ivan in its domain.
            </div>
            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                Jarvis, can you tell me when was this website created.
            </div>
            <div class="message sent">
                <p class="jarvis_name">Jarvis:</p>
                Sure sir, wait a minute...
            </div>
        </div>
    </div>

    <div class="form">
        <form action="./4_reverse.php" method="post">
            <div class="input"><input type="text" placeholder="yyyy-mm-dd" name="date" required></div>
            <div class="submit"><input type="submit" value="Proceed"></div>
        </form>
    </div>

    </body>

    </html>
<?php } else {
    redirect("../");
} ?>