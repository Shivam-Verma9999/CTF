<?php

function redirect( string $url ) {
   ob_start();
   header( 'Location:' . $url );
   ob_end_flush();
   die();
}

function check_for_user( $conn, $table_name, $email ) {
   $query = "SELECT * FROM {$table_name} WHERE name = ?";
   $stmt = $conn->prepare( $query );
   $stmt->bind_param( 's', $email );
   $stmt->execute();
   $result = $stmt->get_result();
   return ( int )$result->num_rows;
}

//Function to send activation link
function send_activation_link( $name, $email, $conf_code ) {
   $subject = "Varify your email";
   $conf_link = "cesmmmut.000webhostapp.com/subscribe/info.php?conf_code=" . $conf_code;
   $content = "<html>
    <head> 
        <title> Email Confirmation</title>
    </head>
    <body style='padding: 0px; margin: 0px;'>
        <div style='width: 100%; height: 100%; background: grey;'>
            <center><div>
            <div style='width: 100%; font-size: 45px; text-align: center; position: absolute; top: 7%'> CES MMMUT</div>
            <div style='width: 45%; height: 60%; border-radius: 6px; left: 50%; top: 50%; transform: translate(-50%, -50%); position: absolute; background: white; padding: 25px; box-sizing: border-box; font-family: monospace; min-width: 301px;'>
                <span style='font-size: 16px;'> Hi,  {$name}</span> <br><br>
                <span style='font-size: 14px; font-family: sans-serif;'>Please confirm <a href='mailto:{$email}'>{$email}</a>. Thank you and happy coding.</span><br><br>
                <center><a href='{$conf_link}' ><input type='button' value='Confirm Email Address' style='width: 250px; height: 40px; border-radius: 8px; font-size: 16px; color: white; background: green; cursor: pointer;'></a></center><br><br><br>
                <div style='text-align: center;'> If you have trouble using the button above, you can also confirm by copying the link below into your address bar:</div><br>
                <div style='text-align: center; font-size: 11px; width: 80%; margin: auto;'><a href='{$conf_link}'>{$conf_link}</a></div><br><br><br>             
                <div style='color: darkgrey; font-weight: bolder; font-size: 12px;'> Cheers</div>  
                <div style='color: grey; font-weight: bolder; font-size: 14px;'> CES Team</div><br>
                <div style='color: grey; font-size: 11px; text-align: center;'>If you did not sign up or request for email confirmation, please ignore this email.</div>
                </div>
            </div>
        </center>
        </div>
    </body>
</html>";
   $message = "";
   $uid = md5( uniqid( time() ) );
   $from_name = "CES MMMUT";
   $from_mail = "cesatmmmut@gmail.com";
   $reply_to = "cesatmmmut@gmail.com";
   $mailTo = $email;
   //Generate Header data
   $header = "From: " . $from_name . "<" . $from_mail . ">\r\n";
   $header .= "Reply-To: " . $reply_to . "\r\n";
   $header .= "MIME-Version: 1.0\r\n";
   $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
   $message = "--" . $uid . "\r\n";
   $message .= "Content-type:text/html; charset=iso-8859-1\r\n";
   $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
   $message .= $content . "\r\n\r\n";
   $message .= "--" . $uid . "\r\n";

   return mail( $mailTo, $subject, $message, $header );
}

function generate_conf_code( $email ) {
   return md5( "super" . $email . "confidential" );
}

//Function to send Notice mail
function send_notice_mail( $mailTo, $name, $title, $content, $issue_date, $associates ) {
   $subject = $title;
   $content = "<html>
<head>
   <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>
    </title>
    <style>
        .big_container{
            top: 0px;
            left: 0px;
            width: 100%;
            height: 100%;
            background-color: white;
            position: absolute;
        }
        .top_text{
            margin-top: 2%;
            margin-left: 25%;
            font-size: 36px;
            color: #9575cd;
            text-decoration: underline black;
            text-shadow: 1px 1px 4px snow;
            font-family: sans-serif;
        }
        
        .first-container
        {
            position: relative;
            margin: auto;
            margin-top: 4%;
            border: 1px solid snow;
            border-radius: 5px;
            background-color: snow;
            box-shadow: 0px 0px 10px grey;
            width: 500px;
            max-width: 100vw;
        }
        .text_style{
            font-family: sans-serif;
            font-size: 20px;
            margin-left: 4%;
        }
        .text_style_2{
            margin-left: 4%;
            font-family:sans-serif;
            font-size: 17px;
            color: #616161;
        }  
.associates {
    border-top: solid 1px #06BCFF;
    padding: 4px;
    margin-top: 5px;
    background: rgb(240, 240, 240);
    overflow: hidden;
}
.style_text_4
        {
            margin-left: 4%;
            font-size: 18px;
        }
        .view_more{
            margin-top: 6%;
            margin-left: 9px;
        }
        a {
            margin-left: 4%;
            text-decoration: none;
            font-family: sans-serif;
            color: #2962ff;
            text-decoration: underline;
        }
    </style>
    </head>
    <body>
    <div class='big_container'>
    <div class='first-container'>
      <h1 class='top_text'> CES MMMUT </h1>
       <h5 class='text_style'> {$title} </h5>
       <h4 style='margin-left: 4%;'>Hi! {$name},</h4>
        <p class='text_style_2'> {$content}.....</p>
        <h4 style='color: #2962ff; margin-left: 4%;'>Issue Date: ".date('M d, Y', strtotime($issue_date))."</h4>
        <p class='view_more'><a href='cesmmmut.000webhostapp.com/notice/'>Read More</a> </p>
        <br/>
    <div class='associates'>
   <p style='font-size: 100%; font-family: sans-serif; font-weight: bold; text-decoration: underline magenta; margin-left:  2%;'>&nbsp;Associates&nbsp;</p>
   <p style='font-family: sans-serif; color:#616161; margin-left: 6%; white-space: pre-line;'> {$associates}
   </div>
   </div>
   </div>
   </body>
</html>";
   
   $message = "";
   $uid = md5( uniqid( time() ) );
   $from_name = "CES MMMUT";
   $from_mail = "cesatmmmut@gmail.com";
   $reply_to = "cesatmmmut@gmail.com";
   //Generate Header data
   $header = "From: " . $from_name . "<" . $from_mail . ">\r\n";
   $header .= "Reply-To: " . $reply_to . "\r\n";
   $header .= "MIME-Version: 1.0\r\n";
   $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
   //Generate message
   $message = "--" . $uid . "\r\n";
   $message .= "Content-type:text/html; charset=iso-8859-1\r\n";
   $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
   $message .= $content . "\r\n\r\n";
   $message .= "--" . $uid . "\r\n";

   return mail( $mailTo, $subject, $message, $header );
}
?>