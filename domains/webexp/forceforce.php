<?php
/**
 * Created by PhpStorm.
 * User: Shivam
 * Date: 3/9/2018
 * Time: 9:27 AM
 */
$flag = 0;
if (isset($_POST['get_key'], $_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if ($username == "sheeran" && $password == "885") {
        $flag = 1;
    } elseif ($username == "watson" && $password == "634") {
        $flag = 2;
    } elseif ($username == "tucker" && $password == "904") {
        $flag = 3;
    }
}
?>

<html>
<head>
    <title>CTF</title>
    <style>
        body {
            background: #c0c6c5;
            display: flex;
        }

        .main {
            margin: auto;
            display: flex;
            background: white;
            width: 500px;
            height: 500px;
            border-radius: 10px;
        }

        .form {
            margin: auto;
            width: 250px;
            height: 250px;
        }

        .tf {
            width: 250px;
            height: 30px;
        }

        .sub {
            margin-left: 80px;
            width: 80px;
            height: 30px;
            background: #494ec6;
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="main">
    <?php if ($flag == 0) { ?>
        <div class="form">
            <form action="./forceforce.php" method="post">
                <input class="tf" type="text" name="username" placeholder="Username" required/><br/><br/>
                <input class="tf" type="password" name="password" placeholder="Password" required/><br/><br/>
                <input class="sub" type="submit" name="get_key" value="Get Key"/>
            </form>
        </div>
    <?php } ?>
    <div>
        <?php if ($flag != 0) { ?>
            <p>login successful</p>
            <?php if ($flag == 1) { ?>
                <p>Hello Mr. Sheeran, You can not access the key with this account</p>
            <?php }
            if ($flag == 2) { ?>
                <p>Hello Mr. Watson, You can not access the key with this account</p>
            <?php }
            if ($flag == 3) { ?>
                <p>Hello Mr. Tucker, How did you escape from SHIELD.<br> By the way, your key is
                    CTF{HailliaHHidfkieldliddslidk}</p>
            <?php }
        } ?>
    </div>
</div>
</body>
</html>
