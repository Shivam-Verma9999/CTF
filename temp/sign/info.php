<?php
require( "./include/connection.php" );
require( "./include/functions.php" );
$mail_sent = 0;
//Handle check request from ajax
if ( isset( $_GET[ 'subscription_check' ] ) ) {
   $email = trim( $_GET[ "email" ] );
   //echo "Email:  $email<br>";
   $conn = create_connection( "MMMUT" );
   $row = check_for_user( $conn, "users", $email );
   if ( $row == 0 )
      echo( "ok" );
   else
      echo( "nope" );
   $conn->close();
   die();
}
?>

<?php

if ( isset($_GET['sign_in'])){
   if( $_GET['name'] !== 'shankar')
      echo('nope');
   else
      echo ("ok");
   die();
}
?>


<?php

if ( isset($_GET['forget_password'])){
   if( $_GET['username'] === 'shankar')
      echo("ok");
   else
      echo("nope");
   die();
}
?>


<?php
if ( isset( $_GET[ 'resend_link' ] ) ) {
   $email = trim( $_GET[ 'email' ] );
   $code = trim( $_GET[ 'code' ] );
   $conn = create_connection( "ces" );
   $row = check_for_user( $conn, "subscribers", $email );
   $conn->close();
   if ( $row == 1 ) {
      echo( "verified" );
   } else {
      $conn = create_connection( "ces" );
      $query = "SELECT * FROM subscriber_varification WHERE email = '{$email}' AND conf_code LIKE '___{$code}%'";
      $result = $conn->query( $query );
      $row_count = ( int )$result->num_rows;
      if ( $row_count == 1 ) {
         $row = $result->fetch_assoc();
         $message = send_activation_link( $row[ 'name' ], $row[ 'email' ], $row[ 'conf_code' ] ) ? "success" : "error";
         echo( $message );
      } else {
         echo( "failed" );
      }
   }
   die();
}
?>

<?php
if ( isset( $_POST[ 'subscribe' ] ) ) {
   $name = htmlspecialchars( trim( $_POST[ 'name' ] ) );
   $email = trim( $_POST[ 'email' ] );
   $designation = trim( $_POST[ 'designation' ] );
   //Check for existing subscriber
   $conn = create_connection( "ces" );
   $row = check_for_user( $conn, "subscribers", $email );
   $conn->close();
   if ( $row == 0 ) {
      $conf_code = generate_conf_code( $email );
      $mail_sent = send_activation_link( $name, $email, $conf_code ) ? 1 : 0;
      //Insert confirmation data into database
      $conn = create_connection( "ces" );
      $query = "INSERT IGNORE INTO subscriber_varification (name, email, designation, conf_code, status) values (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare( $query );
      $status = '0';
      $stmt->bind_param( 'sssss', $name, $email, $designation, $conf_code, $status );
      $stmt->execute();
      $conn->close();
   }
   ?>

   <!DOCTYPE html>
   <html>

   <head>
   	<link rel="shortcut icon" href="../images/ces_logo.jpg" />
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
      <title>The Last Step</title>
   </head>
   <style>
      a {
         text-decoration: none;
      }
      
      header {
         position: absolute;
         width: 100vw;
         z-index: 3;
         display: flex;
         justify-content: space-between;
         /*	background: transparent;*/
         flex-wrap: wrap;
         padding: 8px 20px;
         box-sizing: border-box;
      }
      
      .menu_items a {
         font-family: Open Sans;
         text-decoration: none;
         color: azure;
         display: inline-block;
         padding: 5px;
         background: rgba(250, 250, 250, 0.1);
         letter-spacing: 2px;
         border: solid 1px transparent;
      }
      
      .menu_items a:hover {
         border-color: white;
      }
      
      .ces_logo img {
         height: 50px;
      }
      
      .menu {
         display: flex;
         justify-content: center;
         flex-wrap: wrap;
         min-width: 331px;
      }
      
      .menu_items {
         padding: 5px 20px;
         margin: auto;
      }
      
      .ces_logo span {
         font-size: 150%;
         display: block;
         font-family: Pacifico;
         color: #fff;
      }
      
      .ces_logo span::first-letter {
         font-size: 200%;
      }
      
      body {
         padding: 0;
         margin: 0;
         background: #40c6f7;
         overflow-x: hidden;
         min-height: 464px;
      }
      
      .header {
         width: 100%;
         height: 150px;
         background: ;
      }
      
      #background1,
      #background2,
      #background3 {
         width: 100%;
         height: 100%;
         position: fixed;
         top: 0;
         text-align: center;
      }
      
      #background1 span {
         display: inline-block;
         margin-top: 50%;
         transform: translate(0%, -100%);
         font-size: 500px;
         color: rgba(100, 100, 100, 0.2);
         font-family: Fredericka the Great;
      }
      
      #background2 {
         transform: skewX(-30deg);
      }
      
      #background2 span {
         display: inline-block;
         margin-top: 50%;
         transform: translate(10%, -40%);
         font-size: 500px;
         color: rgba(100, 100, 100, 0.08);
         font-family: Fredericka the Great, monospace;
      }
      
      #message {
         position: absolute;
         margin-left: 10px;
         margin-top: 200px;
         padding: 25px 10px;
         background: rgba(250, 250, 250, 1);
         width: 60%;
         box-sizing: border-box;
      }
      
      #message-header {
         font-size: 30px;
         font-family: Lobster, monospace;
         margin-bottom: 10px;
      }
      
      #message-content {
         font-family: Open Sans, sans-serif;
      }
      
      #note {
         font-family: Open Sans;
      }
      
      .clickable {
         height: 35px;
         font-family: Open Sans;
         cursor: pointer;
         background: greenyellow;
         border: none;
         border-radius: 4px;
         box-shadow: 0px 0px 0px 3px lightgreen;
      }
      
      .non_clickable {
         height: 35px;
         font-family: Open Sans;
         cursor: not-allowed;
         border: none;
         border-radius: 4px;
         box-shadow: 0px 0px 0px 3px #e0e0e0;
      }
      
      #timer {
         display: inline;
         margin-left: 30px;
         font-size: 20px;
      }
      
      .menu {
         display: flex;
         justify-content: center;
         flex-wrap: wrap;
         min-width: 331px;
      }
      
      .menu_items {
         padding: 5px 20px;
         margin: auto;
      }
      
      .burger_menu {
         display: none;
         margin: auto 0px;
         margin-right: 5px;
      }
      
      .burger {
         height: 40px;
         width: 45px;
         cursor: pointer;
      }
      
      .b {
         width: 100%;
         height: 6px;
         background: white;
         border-radius: 10px;
      }
      
      .b-1 {
         border-bottom-left-radius: 0;
         border-bottom-right-radius: 0;
      }
      
      .b-2 {
         margin: 5px 0px;
      }
      
      .b-3 {
         border-top-left-radius: 0;
         border-top-right-radius: 0;
      }
      
      .side_menu {
         transform: translate(110%, 0);
         transition-duration: 0.4s;
         position: absolute;
         height: 100%;
         overflow: auto;
         right: 0;
         width: 60%;
         z-index: 5;
         box-sizing: border-box;
         padding: 100px 5px;
         background: #FFF;
         display: none;
      }
      
      @media (max-width:818px) {
         .burger_menu {
            display: block;
         }
         .menu {
            display: none !important;
         }
         .side_menu {
            display: block !important;
         }
         header {
            justify-content: space-between !important;
         }
         #message {
            width: 100% !important;
            margin-left: 0 !important;
         }
      }
      
      .cross {
         height: 40px;
         width: 40px;
         margin-top: -45px;
         position: relative;
         cursor: pointer;
      }
      
      .c-1 {
         height: 4px;
         width: 100%;
         background: rgba(114, 66, 155, 1.00);
         position: absolute;
         top: 50%;
         transform: rotateZ(-45deg) translate(0, -50%);
      }
      
      .c-2 {
         height: 4px;
         width: 100%;
         background: rgba(114, 66, 155, 1.00);
         position: absolute;
         transform: rotateZ(45deg) translate(0, -50%);
         top: 50%;
      }
      
      .side_menu li {
         list-style-type: none;
         font-family: Open Sans;
         letter-spacing: 2px;
         margin: 7px 0px;
      }
      
      .menu_items a:hover {
         border-color: white;
      }
      
      .side_menu li a {
         color: rgba(36, 66, 83, 1.00);
      }
   </style>

   <body onload="timerHandler();">
      <header>
         <div class="ces_logo"><span>CES</span>
         </div>
         <div class="menu">
            <div class="menu_items"><a href="../">HOME</a>
            </div>
            <div class="menu_items"><a href="../event/">EVENTS</a>
            </div>
            <div class="menu_items"><a href="../notice/">NOTICE</a>
            </div>
            <div class="menu_items"><a href="../gallery/">GALLERY</a>
            </div>
            <div class="menu_items"><a href="../members/">MEMBERS</a>
            </div>
            <div class="menu_items"><a href="./">SUBSCRIBE</a>
            </div>
         </div>
         <div class="burger_menu" onclick="document.querySelector('.side_menu').style.transform='translate(0,0)'; console.log('dsdf');">
            <div class="burger">
               <div class="b-1 b"></div>
               <div class="b-2 b"></div>
               <div class="b-3 b"></div>
            </div>
         </div>
      </header>
      <div class="side_menu">
         <div class="cross" onclick="this.parentNode.style.transform='translate(110%, 0)';">
            <div class="c-1"></div>
            <div class="c-2"></div>
         </div>
         <ul>
            <li><a href="../">HOME</a>
            </li>
            <li><a href="../event/">EVENTS</a>
            </li>
            <li><a href="../notice/">NOTICE</a>
            </li>
            <li><a href="../gallery/">GALLERY</a>
            </li>
            <li><a href="../members/">MEMBERS</a>
            </li>
            <li><a href="./">SUBSCRIBE</a>
            </li>
         </ul>
      </div>

      <?php if($row == 1){ ?>
      <!----------------------First Message Block ------------------------------ -->
      <div id="message">
         <div id="message-header"> <span style="margin-bottom: 10px; display: inline-block; font-family:Open Sans; color: green;"> Email has been already registered.</span> </div>
         <div id="message-content"> If you are not receiving the notifications then check your black list or spam folder. </div>
         <br>
         <div id="note">
            <span><b>NOTE:</b></span>
            <span style="font-style: italic; opacity: 1; color: #999"> For any queries, contact administrator.</span> </div>
         <br>
      </div>

      <?php }elseif($mail_sent == 1){ ?> ?>
      <!-- ----------- Second Message Block --------------------------- -->

      <div id="message">
         <div id="message-header"> <span style="margin-bottom: 10px; display: inline-block">Thank you for your interest in CES</span><br>
            <span style="font-family: Merriweather; font-size: 30px;">ONE LAST STEP !!!</span> </div>
         <div id="message-content"> An email verification page has beed sent to
            <?php echo($email); ?>. Please verfiy your email to start receiving notifications from <a href="http://localhost.000webhost.com" style="text-decoration: none; color: black"><b>CES</b></a> </div>
         <br>
         <div id="note">
            <spam><b>NOTE:</b>
            </spam>
            <span style="font-style: italic; opacity: 1; color: #999"> If you don't see any confirmation email then check your spam or junk folder.</span> </div>
         <br>
         <button id="timer_btn" type="button" class="clickable" data-email="<?php echo($email); ?>" data-code="<?php echo(substr($conf_code, 3, 5)); ?>" onclick="sendData(this);">Resend Verification</button>
         <div id="timer">5 : 00</div>
      </div>
      <script>
         function timerHandler() {
            document.getElementById( "timer_btn" ).setAttribute( "class", "non_clickable" );
            document.getElementById( "timer_btn" ).setAttribute( "disabled", "disabled" );
            var time = 300;
            var x;
            var comedown = function () {
               time--;
               var s = parseInt( time / 60 ) + " : " + ( time % 60 < 10 ? ( "0" + time % 60 ) : time % 60 );
               if ( time == 0 ) {
                  clearInterval( x );
                  document.getElementById( "timer_btn" ).removeAttribute( "disabled" );
                  document.getElementById( "timer_btn" ).setAttribute( "class", "clickable" );
                  document.getElementById( "timer" ).innerHTML = "";
                  return;
               }
               document.getElementById( "timer" ).innerHTML = s;
            }
            x = setInterval( comedown, 1000 );

         }

         function sendData( button ) {
            var email = button.dataset.email;
            var code = button.dataset.code;
            console.log(email);
            console.log(code);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
               if ( this.readyState == 4 && this.status == 200 ) {
                  text = this.responseText;
                  console.log(text);
                  if ( text.search("success") >= 0 ) {
                     timerHandler();
                  } else if( text.search("verified") >= 0 ){
                     button.parentNode.removeChild(button);
                     document.getElementById("timer").parentNode.removeChild(document.getElementById("timer"));
                  } else if( text.search("error") ){
                      document.getElementById("timer").innerHTML = "<span style='font-style: italic; opacity: 1; color: #999'> Request failed, Try again !!!</span>";
                  }
               }
            };
            xhttp.open( "GET", "./info.php?resend_link=true&email="+email+"&code="+code, true );
            xhttp.send();
         }
      </script>
      <?php } ?>
   </body>

   </html>

   <!--- Handle Confirmation request -->

   <?php
} elseif ( isset( $_GET[ 'conf_code' ] ) ) {
      $conf = 0;
      $info = "";
      $conf_code = $_GET[ 'conf_code' ];
      $conn = create_connection( "ces" );
      $query = "SELECT * FROM subscriber_varification WHERE conf_code = ?";
      $stmt = $conn->prepare( $query );
      $stmt->bind_param( 's', $conf_code );
      $stmt->execute();
      $result = $stmt->get_result();
      $rows = ( int )$result->num_rows;
      $conn->close();
      if ( $rows == 1 ) {
         $conf = 1;
         //Check for cofirmation status
         $status = "";
         $conn = create_connection( "ces" );
         $query = "SELECT status FROM subscriber_varification WHERE conf_code = ?";
         $stmt = $conn->prepare( $query );
         $stmt->bind_param( 's', $conf_code );
         $stmt->bind_result( $status );
         $stmt->execute();
         $stmt->fetch();
         $conn->close();
         if ( $status == "1" ) {
            $info = "Email has been already verified. You will receive our notification and updates regularly.";
         } else {
            //Copy data to subscribers table
            $conn = create_connection( "ces" );
            $query = "INSERT INTO subscribers (name, email, designation) SELECT name, email, designation FROM subscriber_varification WHERE conf_code = ?";
            $stmt = $conn->prepare( $query );
            $stmt->bind_param( 's', $conf_code );
            $stmt->execute();
            $conn->close();
            //Change status for email
            $conn = create_connection( "ces" );
            $query = "UPDATE subscriber_varification SET status = '1' WHERE conf_code = ?";
            $stmt = $conn->prepare( $query );
            $stmt->bind_param( 's', $conf_code );
            $stmt->execute();
            $conn->close();
            $info = "Email successfully verified. You can now recieve latest notifications and updates from Computer Engineering Society, MMMUT";
         }
      }
      ?>

      <!DOCTYPE html>
      <html>

      <head>
      		<link rel="shortcut icon" href="../images/ces_logo.jpg" />
         <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico">
         <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
         <title>The Last Step</title>
      </head>
      <style>
         a {
            text-decoration: none;
         }
         
         header {
            position: absolute;
            width: 100vw;
            z-index: 3;
            display: flex;
            justify-content: space-between;
            /*	background: transparent;*/
            flex-wrap: wrap;
            padding: 8px 20px;
            box-sizing: border-box;
         }
         
         .menu_items a {
            font-family: Open Sans;
            text-decoration: none;
            color: azure;
            display: inline-block;
            padding: 5px;
            background: rgba(250, 250, 250, 0.1);
            letter-spacing: 2px;
            border: solid 1px transparent;
         }
         
         .menu_items a:hover {
            border-color: white;
         }
         
         .ces_logo img {
            height: 50px;
         }
         
         .menu {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            min-width: 331px;
         }
         
         .menu_items {
            padding: 5px 20px;
            margin: auto;
         }
         
         .ces_logo span {
            font-size: 150%;
            display: block;
            font-family: Pacifico;
            color: #fff;
         }
         
         .ces_logo span::first-letter {
            font-size: 200%;
         }
         
         body {
            padding: 0;
            margin: 0;
            background: #40c6f7;
            overflow-x: hidden;
            min-height: 464px;
         }
         
         .header {
            width: 100%;
            height: 150px;
            background: ;
         }
         
         #background1,
         #background2,
         #background3 {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            text-align: center;
         }
         
         #background1 span {
            display: inline-block;
            margin-top: 50%;
            transform: translate(0%, -100%);
            font-size: 500px;
            color: rgba(100, 100, 100, 0.2);
            font-family: Fredericka the Great;
         }
         
         #background2 {
            transform: skewX(-30deg);
         }
         
         #background2 span {
            display: inline-block;
            margin-top: 50%;
            transform: translate(10%, -40%);
            font-size: 500px;
            color: rgba(100, 100, 100, 0.08);
            font-family: Fredericka the Great, monospace;
         }
         
         #message {
            position: absolute;
            margin-left: 10px;
            margin-top: 200px;
            padding: 25px 10px;
            background: rgba(250, 250, 250, 1);
            width: 60%;
            box-sizing: border-box;
         }
         
         #message-header {
            font-size: 30px;
            font-family: Lobster, monospace;
            margin-bottom: 10px;
         }
         
         #message-content {
            font-family: Open Sans, sans-serif;
         }
         
         #note {
            font-family: Open Sans;
         }
         
         .clickable {
            height: 35px;
            font-family: Open Sans;
            cursor: pointer;
            background: greenyellow;
            border: none;
            border-radius: 4px;
            box-shadow: 0px 0px 0px 3px lightgreen;
         }
         
         .non_clickable {
            height: 35px;
            font-family: Open Sans;
            cursor: not-allowed;
            border: none;
            border-radius: 4px;
            box-shadow: 0px 0px 0px 3px #e0e0e0;
         }
         
         #timer {
            display: inline;
            margin-left: 30px;
            font-size: 20px;
         }
         
         .menu {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            min-width: 331px;
         }
         
         .menu_items {
            padding: 5px 20px;
            margin: auto;
         }
         
         .burger_menu {
            display: none;
            margin: auto 0px;
            margin-right: 5px;
         }
         
         .burger {
            height: 40px;
            width: 45px;
            cursor: pointer;
         }
         
         .b {
            width: 100%;
            height: 6px;
            background: white;
            border-radius: 10px;
         }
         
         .b-1 {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
         }
         
         .b-2 {
            margin: 5px 0px;
         }
         
         .b-3 {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
         }
         
         .side_menu {
            transform: translate(110%, 0);
            transition-duration: 0.4s;
            position: absolute;
            height: 100%;
            overflow: auto;
            right: 0;
            width: 60%;
            z-index: 5;
            box-sizing: border-box;
            padding: 100px 5px;
            background: #FFF;
            display: none;
         }
         
         @media (max-width:818px) {
            .burger_menu {
               display: block;
            }
            .menu {
               display: none !important;
            }
            .side_menu {
               display: block !important;
            }
            header {
               justify-content: space-between !important;
            }
            #message {
               width: 100% !important;
               margin-left: 0 !important;
            }
         }
         
         .cross {
            height: 40px;
            width: 40px;
            margin-top: -45px;
            position: relative;
            cursor: pointer;
         }
         
         .c-1 {
            height: 4px;
            width: 100%;
            background: rgba(114, 66, 155, 1.00);
            position: absolute;
            top: 50%;
            transform: rotateZ(-45deg) translate(0, -50%);
         }
         
         .c-2 {
            height: 4px;
            width: 100%;
            background: rgba(114, 66, 155, 1.00);
            position: absolute;
            transform: rotateZ(45deg) translate(0, -50%);
            top: 50%;
         }
         
         .side_menu li {
            list-style-type: none;
            font-family: Open Sans;
            letter-spacing: 2px;
            margin: 7px 0px;
         }
         
         .menu_items a:hover {
            border-color: white;
         }
         
         .side_menu li a {
            color: rgba(36, 66, 83, 1.00);
         }
      </style>

      <body>
         <header>
            <div class="ces_logo"><span>CES</span>
            </div>
            <div class="menu">
               <div class="menu_items"><a href="home.html">HOME</a>
               </div>
               <div class="menu_items"><a href="event.html">EVENTS</a>
               </div>
               <div class="menu_items"><a href="notice.html">NOTICE</a>
               </div>
               <div class="menu_items"><a href="#">GALLERY</a>
               </div>
               <div class="menu_items"><a href="members.html">MEMBERS</a>
               </div>
               <div class="menu_items"><a href="http://mmmut.ac.in">MMMUT</a>
               </div>
            </div>
            <div class="burger_menu" onclick="document.querySelector('.side_menu').style.transform='translate(0,0)'; console.log('dsdf');">
               <div class="burger">
                  <div class="b-1 b"></div>
                  <div class="b-2 b"></div>
                  <div class="b-3 b"></div>
               </div>
            </div>
         </header>
         <div class="side_menu">
            <div class="cross" onclick="this.parentNode.style.transform='translate(110%, 0)';">
               <div class="c-1"></div>
               <div class="c-2"></div>
            </div>
            <ul>
               <li><a href="home.html">HOME</a>
               </li>
               <li><a href="event.html">EVENTS</a>
               </li>
               <li><a href="notice.html">NOTICE</a>
               </li>
               <li><a href="#">GALLERY</a>
               </li>
               <li><a href="members.html">MEMBERS</a>
               </li>
               <li><a href="subscribe.html">SUBSCRIBE</a>
               </li>
            </ul>
         </div>

         <?php if($conf == 1){ ?>
         <!----------------------First Message Block ------------------------------ -->
         <div id="message">
            <div id="message-header"> <span style="margin-bottom: 10px; display: inline-block; font-family:Open Sans; color: green;">You are our subscriber !!</span> </div>
            <div id="message-content">
               <?php echo($info); ?> </div>
         </div>
         <br>
         <div id="note">
            <span><b>NOTE:</b></span>
            <span style="font-style: italic; opacity: 1; color: #999"> For any queries, contact administrator.</span> </div>
         <br>
         </div>
         <?php }else{ ?>
         <!-- ----------- Second Message Block --------------------------- -->

         <div id="message">
            <div id="message-header"> <span style="margin-bottom: 10px; display: inline-block; font-family:Open Sans; color: green;"> Wait Wait !!</span> </div>
            <div id="message-content"> Confirmation link is invalid. Please do not alter the url sent to your mail for confirmation. </div>
            <br>
            <div id="note">
               <span><b>NOTE:</b></span>
               <span style="font-style: italic; opacity: 1; color: #999"> For any queries, contact administrator.</span> </div>
            <br>
         </div>
         <?php } ?>

      </body>

      </html>
      <?php }else{redirect("./");} ?>