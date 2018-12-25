<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 2/19/2018
 * Time: 8:47 PM
 */

function log_error(string $error){
    $fp = fopen("/apache24/htdocs/CTF/log/log.txt", "a");
    if(flock($fp, LOCK_EX)){
        fwrite($fp, (string) date("Y, M d H:i:s", strtotime("now")));
        fwrite($fp, "\n");
        fwrite($fp, $error);
        fwrite($fp, "\n\n\n");
    }
    fflush($fp);
    return flock($fp, LOCK_UN);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlspecialchars($data, ENT_QUOTES);
    return $data;
}

function redirect(string $url){
    ob_start();
    Header("Location: " . $url);
    ob_end_flush();
    die();
}

function mail_confirmation_code($name, $email, $conf_code){
    $subject = "Verify your email for CTF";
    $conf_link = "mmmutctf.000webhostapp.com/start/info.php?confcode=". $conf_code;
    $content = "<html>
<head>
    <title>MMMUT CTF</title>
    <style>
        .main {
            position: relative;
            margin: auto;
            width: 500px;
            font-size: large;
        }

        .logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .button {
            background: #2979ff;
            color: white;
            text-align: center;
            width: 200px;
            height: 35px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            position: relative;
        }

        .button div {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
<div class='main'>
    <div style='position: relative; height: 180px;'><img class='logo' src='../images/CTF.svg' width='150'></div>
    <hr/>
    <h2>Welcome to MMMUT CTF</h2>
    <p>Hi {$name}, </p>
    <p>
        We are super excited to have you join the MMMUT CTF community
        and look forward to see you learning actionable cybersecurity skills
        and climb the Leaderboard.
    </p>
    <p>Please click the button below to confirm your email</p>
    <br/><a href='$conf_link'>
        <div class='button'>
            <div>Confirm</div>
        </div>
    </a>
    <p>Thanks!</p>
    <p>CES Team, MMMUT</p>
</div>
</body>
</html>";
    $uid = md5(uniqid(time()));
    $from_name = "CTF Team, MMMUT";
    $from_mail = "cesatmmmut@gmail.com";
    $reply_to = "cesatmmmut@gmail.com";
    $mailTo = $email;
    //Generate Header Data
    $header = "From: " . $from_name . "<" . $from_mail . ">\r\n";
    $header .= "Reply-To: " . $reply_to . "\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
    //Generate the payload
    $message = "--" . $uid . "\r\n";
    $message .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= $content . "\r\n\r\n";
    $message .= "--" . $uid . "\r\n";

    return mail($mailTo, $subject, $message, $header);
}

function mail_reset_link($name, $email, $key){
    $subject = "Reset Your CTF password";
    $reset_link = "mmmutctf.000webhostapp.com/start/reset_handler.php?reset=true&key=". $key;
    $content = "<html>
<head>
    <title>MMMUT CTF</title>
    <style>
        .main {
            position: relative;
            margin: auto;
            width: 500px;
            font-size: large;
        }

        .logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .button {
            background: #2979ff;
            color: white;
            text-align: center;
            width: 250px;
            height: 35px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            position: relative;
        }

        .button div {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
<div class='main'>
    <div style='position: relative; height: 180px;'><img class='logo' src='../images/CTF.svg' width='150'></div>
    <hr/>
    <h2>Welcome to MMMUT CTF</h2>
    <p>Hi {$name}, </p>
    <p>
        We are super excited to have you join the MMMUT CTF community
        and look forward to see you learning actionable cybersecurity skills
        and climb the Leaderboard.
    </p>
    <p>This link will expire in One Hour</p>
    <p>Please click the button below to reset your account password</p>
    <br/><a href='{$reset_link}'>
        <div class='button'>
            <div>Reset Password</div>
        </div>
    </a>
    <p>If, you did not send the reset link, simply ignore this mail.</p>
    <p>Thanks!</p>
    <p>CES Team, MMMUT</p>
</div>
</body>
</html>";

    $uid = md5(uniqid(time()));
    $from_name = "CTF Team, MMMUT";
    $from_mail = "cesatmmmut@gmail.com";
    $reply_to = "cesatmmmut@gmail.com";
    $mailTo = $email;
    //Generate Header Data
    $header = "From: " . $from_name . "<" . $from_mail . ">\r\n";
    $header .= "Reply-To: " . $reply_to . "\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
    //Generate the payload
    $message = "--" . $uid . "\r\n";
    $message .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= $content . "\r\n\r\n";
    $message .= "--" . $uid . "\r\n";

    return mail($mailTo, $subject, $message, $header);
}

function generate_confirmation_key(string $key){
    $salt = "supervertrouwelijkvooradmin";
    $hash = md5($salt . $key . $salt);
    return $hash;
}

function generate_reset_key($key){
    $temp = "Budiansky" . $key . "Hinsley" . $key . "Haufler" . time();
    $hash = md5($temp);
    return $hash;
}

function generate_cookie_data(string $key){
    $temp = "Fujiwara" . $key . "Minamoto" . $key . "Taira";
    $hash = md5($temp);
    return $hash;
}

function initiate_session($username){
    $conn = create_connection();
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $_SESSION['username'] = $username;
    $_SESSION['tutorial_level'] = (int)$row['tutorial_level'];
}