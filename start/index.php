<?php
session_start();
include("../include/connection.php");
include("../include/functions.php");
?>

<html>

<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/sign-up.css">
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

<div id="form-main-div">
    <div id="form-div">
        <div id="form-menu">
            <div class="tab-item active" id="sign-in-tab" onclick="if( ! running ) {running = true; showSignIn(this);} ">Sign In</div>
            <div class="tab-item" id="sign-up-tab" onclick="if( ! running ) { running = true; showSignUp(this);}"> Sign Up</div>
        </div>
        <div id="form-content">
            <div id="sign-in-form">
                <div id="sign-in-left">
                    <div id="forget-password-form-control">
                        <div id="forget-password-container">
                            <div class="message-box" id="forget-password-message">* Given detail didn't matched any data</div>
                            <input class="form-control" id="forget-email" type="email" name="username" value="" placeholder="Email" required/>
                            <input class="form-control" type="button" name="submit" value="Send Password Reset" style="cursor: pointer;" onclick="sendForgetPassword();"><br>
                            <span id="forget-password" onclick="if( ! running ){ running = true; showSignForm(this);}">Sign In Form</span><br>
                            <div id="forget-password-confirmation" style="visibility: hidden;">
                                <span style="color: #00e600; display:inline-block; margin-top: 6px; margin-bottom: 15px;">We just sent a password reset email to <b id="forget-email-detail"></b></span><br>
                                <span style="color: #707070">When you receive the email, click on the link inside to reset your password.</span><br>
                                <span style="color: #707070; display: inline-block; margin-top: 5px;">If you don't see the email after a few minutes, check your spam folder.</span>
                            </div>
                        </div>
                    </div>
                    <div id="sign-in-form-controls">
                        <div id="sign-in-form-container">
                            <form action="../domains/index.php" method="post">
                            <input class="form-control" id="sign-in-username" type="text" name="email" placeholder="Email" required/>
                            <div><input class="form-control" id="sign-in-password" type="password" name="password" placeholder="Password" required/><span id="show-password" onmousedown="document.querySelector('#sign-in-password').setAttribute('type', 'text');" onmouseup="document.querySelector('#sign-in-password').setAttribute('type', 'password');"></span></div>
                            <input class="form-control" type="submit" name="authenticate" value="Sign In" style="cursor: pointer;"><br>
                            <span id="forget-password" onclick="if( ! running ) { running = true; showForgetPassword(this);}">Forgot Password</span>
                            <?php if(isset($_GET['authentication']) && $_GET['authentication'] == 'failed'){ ?>
                            <div style="color:red;">Email or Password did not match</div>
                            <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="sign-in-right"></div>
            </div>
            <div id="sign-up-form">
                <div id="sign-up-left">
                    <input class="form-control" id="name-control" type="text" name="name" value="" placeholder="Name" required onkeyup="validateName();" />
                    <div class="message-box" id="name-message"></div>
                    <input class="form-control" id="username-control" type="text" name="username" value="" placeholder="Username" onkeyup="validateUsername(this)" required/>
                    <div class="message-box" id="username-message">* Username already taken</div>
                    <input class="form-control" id="email-control" type="email" name="email" value="" placeholder="Email" onkeyup="validateEmailAjax();" required/>
                    <div class="message-box" id="email-message">* Enter a valid email</div>
                    <input class="form-control" id="mobile-number-control" type="number" name="mobile" value="" placeholder="Mobile No." required onkeyup="validateMobile(this)" />
                    <div class="message-box" id="mobile-message">* Should be 10 digits long</div>
                </div>
                <div id="sign-up-right">
                    <div>
                        <input class="form-control" id="password-control" type="password" name="password" value="" placeholder="Password" required onkeyup='validatePassword();' />
                        <span id="show-password" onmousedown="document.querySelector('#password-control').setAttribute('type', 'text');" onmouseup="document.querySelector('#password-control').setAttribute('type', 'password');"></span>
                    </div>
                    <div>
                        <div class="message-box" id="password-message">* Minimum 8 characters long</div>
                        <input class="form-control" id="confirm-control" type="password" name="confirm-password" value="" placeholder="Confirm Password" required onkeyup="validateConfirmation(this);" />
                        <span id="show-password" onmousedown="document.querySelector('#confirm-control').setAttribute('type', 'text');" onmouseup="document.querySelector('#confirm-control').setAttribute('type', 'password');"></span>
                    </div>
                    <div class="message-box" id="confirm-password">* Password didn't match</div>
                    <input class="form-control" id="college-control" type="text" name="college" value="" placeholder="College" required/>
                    <div class="message-box"></div>
                    <input class="form-control" type="button" name="submit" value="Register" style="cursor: pointer;" onclick="submitSignUp();"><br>
                </div>
            </div>
            <div id="email-confirmation">
                <div id="loading" style="height: 100%; background: white; z-index: 14; display: none;">
                    <div id="loader" style="height: 100px; width: 100px; border-radius: 100px; border:solid 10px white; border-left-color: rgba(0,0,0,0); border-right-color: rgba(0,0,0,0); border-bottom-left-radius: 100px; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); animation: loading 1.5s infinite ease-in-out; z-index: 2"></div>
                    <div style="height: 118px; width: 118px; background: linear-gradient(#0d47a1 , #64dd17); position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); border-radius: 100px;"></div>
                    <div style="height: 101px; width: 101px; position: absolute; top: 50%; left:50%; transform: translate(-50%, -50%); background: white; border-radius: 90px;"></div>
                </div>
                <div id="mail-confirmation-message">
                </div>
                <div id="sign-in-right"></div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="../js/join.js"></script>
</html>