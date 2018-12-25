<?php
session_start();
include("../include/connection.php");
include("../include/functions.php");

//Handle Logout request
if(isset($_GET['logout']) && $_GET['logout'] == "true"){
    ob_start();
    session_destroy();
    setcookie("CTFCookie", generate_cookie_data($row['email']), time()-3*24*60*60);
    Header("Location: ../");
    ob_end_flush();
    die();
}

//Authenticate user for singin request
if(isset($_POST['email'], $_POST['password'], $_POST['authenticate']) && $_POST['email'] != null && $_POST['password'] != null && $_POST['authenticate'] != null){
    $email = test_input($_POST['email']);
    $password = $_POST['password'];
    $conn = create_connection();
    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        initiate_session($row['username']);
        setcookie("CTFCookie", generate_cookie_data($row['email']), time()+3*24*60*60);
        $conn->close();
    }else{
        $conn->close();
        redirect("../start/?authentication=failed");
    }
}

//Check for cookie data
if(isset($_COOKIE['CTFCookie']) && $_COOKIE['CTFCookie'] != null){
    $cookie = trim($_COOKIE['CTFCookie']);
    //Validate Cookie
    $conn = create_connection();
    $query = "SELECT * FROM users WHERE cookie_data = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cookie);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1){
        //Set the session variables
        $row = $result->fetch_assoc();
        initiate_session($row['username']);
        $conn->close();
    }
}
//Check for active session
if(isset($_SESSION['username']) && $_SESSION['username'] != null){
    switch ($_SESSION['tutorial_level']){
        case 0:
            redirect("./tutorials/1_entry.php");
            break;
        case 1:
            redirect("./tutorials/2_script.php");
            break;
        case 2:
            redirect("./tutorials/3_inject.php");
            break;
        case 3:
            redirect("./tutorials/1_entry.php");
            break;
    }
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>CTF domain</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Jockey+One">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="../css/domain.css">
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
            <li><a href="./index.php?logout=true">Logout</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="choose_domain">
        <h1>CHOOSE YOUR DOMAIN
            <br>
            <span style="font-size: 55%; color: rgba(136,170,163,1.00); font-weight: 100;">PUT YOUR SKILLS TO THE TEST<br>
		<span style="font-size: 90%; color: #03a944;">Score: 00 </span>
            <br>
        <span style="font-size: 90%; color: #03a944;">Rank: 00 </span>
        </span>
        </h1>
    </div>
    <section>
        <div class="domain" onclick="show_domain(this, 1);">
            <p>WEB EXPLOITATIONS</p>
        </div>
        <div class="domain" onclick="show_domain(this,2);">
            <p>CRYPTOGRAPHY</p>
        </div>
        <div class="domain" onclick="show_domain(this,3);">
            <p>FORENSIC</p>
        </div>
        <div class="domain" onclick="show_domain(this,4);">
            <p>NETWORKING</p>
        </div>
        <div class="domain" onclick="show_domain(this,5);">
            <p>LINUX</p>
        </div>
    </section>
</main>
<div class="web_exploitation_questions questions a">
    <div class="top_heading">
        <center>
            <h1>Web Exploitation</h1>
        </center>
        <br>
        <center>
            <div class="close_question" onClick="close_domain();">Choose Other domain</div>
        </center>
    </div>
</div>

<div class="web_exploitation_questions questions b">
    <div class="top_heading">
        <center>
            <h1>Cryptography</h1>
        </center>
        <br>
        <center>
            <div class="close_question" onClick="close_domain();">Choose Other domain</div>
        </center>
    </div>
</div>

<div class="web_exploitation_questions questions c">
    <div class="top_heading">
        <center>
            <h1>Forensic</h1>
        </center>
        <br>
        <center>
            <div class="close_question" onClick="close_domain();">Choose Other domain</div>
        </center>
    </div>
</div>

<div class="web_exploitation_questions questions d">
    <div class="top_heading">
        <center>
            <h1>Networking</h1>
        </center>
        <br>
        <center>
            <div class="close_question" onClick="close_domain();">Choose Other domain</div>
        </center>
    </div>
</div>

<div class="web_exploitation_questions questions e">
    <div class="top_heading">
        <center>
            <h1>Linux</h1>
        </center>
        <br>
        <center>
            <div class="close_question" onClick="close_domain();">Choose Other domain</div>
        </center>
    </div>
</div>
</body>
<script>
    var questions_id;
    var ques;

    function show_domain(a, id) {
        questions_id = "" + id;
        var index = document.querySelectorAll('.domain');
        for (var i = 0; i < index.length; i++) {
            index[i].style.opacity = "0";
            if (index[i] == a) {
                index[i].style.transitionDuration = "0.7s";
                index[i].style.transform = "scale(1.2, 1.2)";
            }
        }
        setTimeout(function () {
            hide_main();
        }, 500);
    }

    function hide_main() {
        var main = document.querySelector('main');
        main.style.opacity = "0";
        main.style.transform = "translate(-100%, 0)";

        switch (questions_id) {
            case "1" :
                ques = document.querySelector(".a");
                break;
            case "2" :
                ques = document.querySelector(".b");
                break;
            case "3" :
                ques = document.querySelector(".c");
                break;
            case "4" :
                ques = document.querySelector(".d");
                break;
            case "5" :
                ques = document.querySelector(".e");
                break;
            case "6" :
                ques = document.querySelector(".f");
                break;
        }
        ques.style.transform = "translate(0, 0)";
    }

    function close_domain() {
        ques.style.transform = "translate(100%, 0)";
        var main = document.querySelector('main');
        main.style.opacity = "1";
        main.style.transform = "translate(0, 0)";

        var index = document.querySelectorAll('.domain');
        for (var i = 0; i < index.length; i++) {
            index[i].style.opacity = "1";
            index[i].style.transform = "scale(1, 1)";

        }
    }
</script>
</html>
<?php 
}else{
    redirect("../start/");
}
?>