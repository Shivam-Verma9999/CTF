<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/9/2018
 * Time: 12:06 PM
 */

session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "webexp_clear";

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
        <script type="text/javascript" src="./jQuery.js"></script>
        <title>CTF</title>
        <style>
            #clear {
                width: 100px;
                height: 40px;
                background: #2979ff;
                color: white;
                margin-left: 100px;
            }
        </style>
    </head>

    <body onload="ll();">
    <div class="main">
        <div class="message_pane">
            <div class=" message recieved">
                <p class="jarvis_name">Jarvis:</p>
                Sir, generally what does a clear button do in a web page.
            </div>
            <div class="message sent">
                <p class="tony_name">Stark:</p>
                It clears some sort of content anywhere. Why are you asking such a monotonous question.
            </div>

            <div class=" message recieved">
                <p class="jarvis_name">Jarvis:</p>
                I have got a clear button that alerts "Data sent Successfully". And there was no data to clear.
            </div>
            <input type="button" id="clear" class="form-btn large important" value="Clear">
            <div class=" message recieved">
                What could that mean.
            </div>
            <div class=" message sent">
                <p class="tony_name">Stark:</p>
                Let me see. May be I could find any key for you.
            </div>
        </div>
    </div>

    <div class="form">
        <form action="./clear.php" method="post">
            <div class="input"><input type="text" name="key" required /></div>
            <div class="submit"><input type="submit" value="Proceed"></div>
        </form>
    </div>

    </body>
    <script type="text/javascript">
        document.getElementById('clear').addEventListener('click', function () {
            alert("Data sent successfully");
        });
    </script>
    </html>
<?php } else {
    redirect("../");
} ?>