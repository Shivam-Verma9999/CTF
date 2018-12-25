<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/8/2018
 * Time: 5:19 PM
 */

session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "tutorial005";

if (isset($_SESSION['username']) && $_SESSION['tutorial_level'] == 4) {
//Validate the answer and submit
    if (isset($_POST['submit'], $_POST['latitude'], $_POST['longitude']) && $latitude == "29.5" && $longitude == "30.4") {
        $latitude = trim($_POST['latitude']);
        $longitude = trim($_POST['longitude']);
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
            $query = "UPDATE users SET tutorial_level = 5 WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $conn->close();
            $_SESSION['tutorial_level'] = 5;
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
                <p class="tony_name">Stark:</p>
                That is exactly the guy. All we need now is his geo location.
            </div>
            <div class="message sent">
                <p class="jarvis_name">Jarvis:</p>
                I can't figure out what does this division mean.
            </div>
            <div style="width: 300px; height: 300px; background: green;"></div>
            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                Let me see it. It may contain some information about its geo location.
            </div>
            <div class=" message sent">
                <p class="jarvis_name">Jarvis:</p>
                Is this an image or simple division with green background.
            </div>
            <div class=" message recieved">
                <p class="tony_name">Stark:</p>
                May be both.
            </div>
        </div>
    </div>

    <div class="inject_form">
        <form action="./5_show_me.php" method="post">
            <input type="text" placeholder="Latitude" name="latitude" required><br>
            <input type="text" placeholder="Longitude" name="longitude" required><br>
            <input type="submit" value="Proceed">
        </form>
    </div>

    </body>

    </html>
<?php } else {    redirect("../");} ?>