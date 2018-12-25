<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/9/2018
 * Time: 9:24 AM
 */

session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "webexp_force";

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
                Sir, I have got a login portal inside hydra network. It is providing any kind key.<br/>
                <a href="./forceforce.php" target="_blank">ForceForce</a>
            </div>
            <div class="message sent">
                <p class="tony_name">Stark:</p>
                Then, did you get the key.
            </div>

            <div class=" message recieved">
                <p class="jarvis_name">Jarvis:</p>
                No sir, But I was able to get two user accounts.<br/>
                Username: sheeran &nbsp; &nbsp; &nbsp; Password: 885 <br/>
                Username: watson &nbsp; &nbsp; &nbsp; Password: 634<br/>
                But these accounts don't have access to the key.
            </div>
            <div class=" message sent">
                <p class="tony_name">Stark:</p>
                Jarvis, Tucker is a (loyal)member of Hydra so he must have an account.
                Try for <br/>
                Username: tucker <br/>
                And any idea about the password.
            </div>
            <div class=" message recieved">
                <p class="jarvis_name">Jarvis:</p>
                As per other passwords, it must be a three digit number.
            </div>
            <div class="message sent">
                <p class="tony_name">Stark:</p>
                Then try for all the combinations of three digits.
            </div>
            <div class=" message recieved">
                <p class="jarvis_name">Jarvis:</p>
                Ok Sir.
            </div>
        </div>
    </div>

    <div class="form">
        <form action="./force.php" method="post">
            <div class="input"><input type="text" name="key" required></div>
            <div class="submit"><input type="submit" value="Proceed"></div>
        </form>
    </div>

    </body>

    </html>
<?php } else {
    redirect("../");
} ?>