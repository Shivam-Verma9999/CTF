<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/9/2018
 * Time: 1:01 PM
 */
session_start();
include("../../include/connection.php");
include("../../include/functions.php");
$_SESSION['question_id'] = "crypto_yummy";

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
            Porridge with fish, oh what a dish.<br/>
            Spaghetti and flour now I need a shower.<br/>
            Toothpaste and cheese I don’t want that please!<br/>
            Mushrooms and cream that’s my dream<br/>
            Apple and Mulberry taste me so YUMMY!
        </div>
        <div class="message sent">
            <p class="tony_name">Stark:</p>
            Jarvis, why are you so interested in foods. Or did you get any virus infection.
        </div>

        <div class=" message recieved">
            <p class="jarvis_name">Jarvis:</p>
            No sir, That is not any virus. I got this poem from secret diary of a super soldier.<br/>
            And some keywords: RSA, d = 187941, N = 4359981234579.<br/>
            What does that mean.
        </div>
        <div class=" message sent">
            <p class="tony_name">Stark:</p>
            RSA encryption/decryption is based on a formula that anyone can find and use,
            as long as they know the values to plug in.<br/>
            But where is the data that is to be decrypted.
        </div>
        <div class=" message recieved">
            <p class="jarvis_name">Jarvis:</p>
            May be we could get some clue from poem.
        </div>
        <div class="message sent">
            <p class="tony_name">Stark:</p>
            Exactly. Let me see.
        </div>
    </div>
</div>

<div class="form">
    <form action="./yummy.php" method="post">
        <div class="input"><input type="text" name="key" required></div>
        <div class="submit"><input type="submit" value="Proceed"></div>
    </form>
</div>

</body>

</html>
<?php } else {
    redirect("../");
} ?>