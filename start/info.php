<?php
include("../include/connection.php");
include("../include/functions.php");
$message = "";

if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['conf_code']) && $_GET['conf_code'] != null){
    $conf_code = trim($_GET['conf_code']);
    $conn = create_connection();
    $query = "SELECT * FROM registration_temp WHERE conf_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $conf_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $conn->close();
    //Check for valid confirmation code
    if($result->num_rows != 1){
        $message = "Invalid confirmation link. Please do not alter the code sent to your mail";
    }else{
        //Check if email has already been confirmed
        $row = $result->fetch_assoc();
        $confirmed = (int)$row['confirmed'];
        if($confirmed == 1){
            $message = "Email has already been confirmed. Visit the sign in page to get forgot password linki";
        }else{
            //Confirm the email
            //Insert the data into users table
            $cookie_data = generate_cookie_data($row['email']);
            $conn = create_connection();
            $query = "INSERT INTO users (username, name, email, mobile, college, password, cookie_data) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssisss", $row['username'], $row['name'], $row['email'], $row['mobile'], $row['college'], $row['password'], $cookie_data);
            $stmt->execute();
            $conn->close();
            //Set the confirmed attribute to 1 in registration_temp
            $conn = create_connection();
            $query = "UPDATE TABLE registration_temp SET confirmed = 1 WHERE conf_code = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $conf_code);
            $stmt->execute();
        }
    }
}else{
    redirect("../");
}
?>

<html>
<head>
    <title>Mail Confirmation</title>
    <style>
        body{
            font-family: monospace;
        }
        .main{
            position: relative;
            width: 70%;
            height: 70%;
            margin: auto;
            background: url("../images/CTF.svg");
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
            border-radius: 10px;
        }
        .cover{
            position: absolute;
            height: 100%;
            width: 100%;
            background: rgba(245,245,245, 0.8);
            border-radius: 10px;
        }
        .content{
            position: absolute;
        }
        .message{
            font-family: Arial;
            color: #1a00a9;
            font-size: 19px;
        }
        header {
            height: 65px;
            display: flex;
        }
        .ctf_logo {
            height: 65px;
            width: 65px;
            margin-left :40px;
        }
        nav {
            height: 40px;
            margin: auto;
            margin-right: 40px;
            display: flex;
        }

        .header_nav_ul {
            margin: 0 !important;
            height: 100%;
        }

        .header_nav_ul li {
            display: inline-block;
            padding: 8px 20px;
        }
        .header_nav_ul a {
            color: #41FF7A;
            font-size: 12px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<header>
    <div class="ctf_logo"><img src="../images/CTF.svg" alt=""></div>
    <nav>
        <ul class="header_nav_ul">
            <li><a href="../">HOME</a></li>
            <li><a href="../leaderboard/">LEADERBOARD</a></li>
            <li><a href="../faqs/">FAQs</a></li>
            <li><a href="../contact/">Contact Us</a></li>
        </ul>
    </nav>
</header>
<div class="main">
    <div class="cover"></div>
    <div class="content"><h1>Welcome to CTF Community</h1>
    <p style="font-size: 17px; font-family: Georgia;">We are super excited to have you join the MMMUT CTF community
        and look forward to see you learning actionable cybersecurity skills
        and climb the Leaderboard.</p><br><br>
        <span class="message"><?php echo($message); ?></span>
        <br><br><br>
        <p>For any queries, contact administrator.</p>
    </div>
</div>
</body>
</html>
