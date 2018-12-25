<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 2/22/2018
 * Time: 11:48 PM
 */
include("../include/connection.php");
include("../include/functions.php");
$key = "";
$flag = 0;
$message = "";
$username = "";
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['reset'], $_GET['key']) && $_GET['reset'] == 'true' && $_GET['key'] != null){
    $key = trim($_GET['key']);
    //Check for valid key
    $conn = create_connection();
    $query = "SELECT username, reset_key, TIMESTAMPDIFF(HOUR, CURRENT_TIMESTAMP, link_time) AS delay FROM password_reset WHERE key = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $result = $stmt->get_result();
    $num_rows = $result->num_rows;
    $row = $result->fetch_assoc();
    $conn->close();
    if($num_rows == 1){
        //Check for expiration of link
        $delay = (int) $row['delay'];
        if($delay > 1){
            $message = "Link has expired. Go to forgot password and send a new link";
        }else{
            //Validate the user for password reset
            $flag = 1;
            $username = $row['username'];
        }
    }else{
        $message = "Please don't alter the link sent to your mail";
    }
}else{redirect("../");}
?>
<html>

<head>
    <title>Forget Password</title>
    <link rel="stylesheet" href="../css/sign-up.css">
</head>

<body>
<div id="form-main-div">
    <div id="form-div">
        <div id="form-content">
            <div id="sign-in-form">
                <div id="sign-in-left">
                    <div id="sign-in-form-controls">
                        <div id="sign-in-form-container">
                            <?php if($flag == 1){ ?>
                            <form id="theForm" action="./reset_handler.php" method="post">
                                <div><input class="form-control" id="new-password" type="password" name="password" value="" placeholder="New Password" required onkeyup='validatePassword();' /><span id="show-password" onmousedown="document.querySelector('#new-password').setAttribute('type', 'text');" onmouseup="document.querySelector('#new-password').setAttribute('type', 'password');"></span></div>
                                <div class="message-box" id="password-message">* Minimum 8 characters</div>
                                <div><input class="form-control" id="confirm-password" type="password" name="conf_password" value="" placeholder="Confirm Password" required onkeyup='validateConfirmation();' /><span id="show-password" onmousedown="document.querySelector('#confirm-password').setAttribute('type', 'text');" onmouseup="document.querySelector('#confirm-password').setAttribute('type', 'password');"></span></div>
                                <div class="message-box" id="confirm-message">* Password didn't match</div>
                                <input type="hidden" name="key" value = "<?php echo($key); ?>"/>
                                <input type="hidden" name="username" value="<?php echo($username); ?>"/>
                                <input class="form-control" type="submit" name="submit" value="Update Password" style="cursor: pointer;" onsubmit="return mysubmitData();"><br>
                            </form>
                            <?php }else{echo("<p>" . $message . "</p>"); }?>
                        </div>
                    </div>
                </div>
                <div id="sign-in-right"></div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    function validatePassword() {
        var elem = document.querySelector('#new-password');
        if (elem.value.length < 8) {
            document.querySelector('#password-message').style.visibility = 'visible';
            return false;
        } else {
            document.querySelector('#password-message').style.visibility = 'hidden';
            return true;
        }
    }

    function validateConfirmation() {
        var elem = document.querySelector('#confirm-password');
        if (elem.value.length == 0) {
            document.querySelector('#confirm-message').innerText = '* Cannot be left blank';
            document.querySelector('#confirm-message').style.visibility = 'visible';
            return false;
        } else if (elem.value !== document.querySelector('#new-password').value) {
            document.querySelector('#confirm-message').innerText = "* Password didn't match";
            document.querySelector('#confirm-message').style.visibility = 'visible';
            return false;
        } else {
            document.querySelector('#confirm-message').style.visibility = 'hidden';
            return true;
        }
    }

    function mysubmitData() {
        if (validatePassword() && validateConfirmation()) {
            return true;
        }
        return false;
    }
</script>

</html>
