<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/8/2018
 * Time: 2:05 AM
 */

session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "tutorial003";

if (isset($_SESSION['username']) && $_SESSION['tutorial_level'] == 2) {
    //Validate the answer and submit
    if (isset($_POST['submit'], $_POST['key']) && $_POST['key'] != null) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $conn = create_connection();
        $query = "SELECT * FROM inject WHERE username = '{$username}' AND password = '{$password}'";
        $result = $conn->query($query);
        if ($result->num_rows != 0) {
            $conn->close();
            //Get the question details
            $conn = create_connection();
            $query = "SELECT * FROM question WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['question_id']);
            $stmt->execute();
            $result = $stmt->get_result();
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
            $query = "UPDATE users SET tutorial_level = 3 WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $conn->close();
            $_SESSION['tutorial_level'] = 3;
            redirect("./4_reverse.php");
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
                We are inside Hydra network sir. All we need now is a Hydra guy to access his account.
            </div>

            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                Jarvis, do you want me to hire a kidnapper.
            </div>
            <div class=" message sent">
                <p class="jarvis_name">Jarvis:</p>
                Why to hire if Hawk eye and Black widow can do it at its best.
            </div>
            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                It's odd for avengers but we do not need any violence at this time. May be Hydra don't have good
                SQL engineers. Try to inject some codes on them.
            </div>
            <div class="message sent">
                <p class="jarvis_name">Jarvis:</p>
                Got that sir. I am at my work....
            </div>
        </div>
    </div>

    <div class="inject_form">
        <form action="./3_inject.php" method="post">
            <input type="text" placeholder="username" name="username" required><br>
            <input type="password" placeholder="password" name="password" required><br>
            <input type="submit" value="Hail Hydra">
        </form>
    </div>

    </body>

    </html>
<?php }else{redirect("../");} ?>