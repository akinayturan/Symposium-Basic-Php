<?php
include('admin/includes/config.php');

function createHash($uzunluk = 16)
{
    $karakterler = 'qwertyuopasdfghjklizxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    return substr(str_shuffle($karakterler), 0, $uzunluk);
}

?>
<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">

    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #dd3d36;
            color: #fff;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #5cb85c;
            color: #fff;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
    <script type="text/javascript">

        function validate() {

            if (document.regform.image.value.length > 0) {
                var extensions = new Array("jpg", "jpeg");
                var image_file = document.regform.image.value;
                var image_length = document.regform.image.value.length;
                var pos = image_file.lastIndexOf('.') + 1;
                var ext = image_file.substring(pos, image_length);
                var final_ext = ext.toLowerCase();
                for (i = 0; i < extensions.length; i++) {
                    if (extensions[i] == final_ext) {
                        return true;
                    }
                }
                alert("Image Extension Not Valid (Use Jpg,jpeg)");
                return false;
            }
        }

    </script>
</head>
<body>
<div class="brand clearfix">

    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">&nbsp;<a href="/">HOME PAGE</a>
    </h4>
    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">|</h4>
    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">&nbsp;<a href="/user/">SIGN IN</a>
    </h4>
    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">|</h4>

    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">&nbsp;<a href="/user/signup.php">SIGN
            UP</a></h4>
    <span class="menu-btn"><i class="fa fa-bars"></i></span>
    <ul class="ts-profile-nav">

        <li class="ts-account">
            <a href="/">ISESER2019 </a>

        </li>
    </ul>
</div>
<div class="login-page bk-img">
    <div class="form-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">


                    <h2 class="page-title">Change Password</h2>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">Form fields</div>
                                <div class="panel-body">
                                    <?php if ($error) { ?>
                                        <div class="errorWrap"><strong>ERROR</strong>
                                        :<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?>
                                        <div class="succWrap"><strong>SUCCESS</strong>
                                        :<?php echo htmlentities($msg); ?> </div><?php } ?>
                                    <form class="form-horizontal" action="" method="POST">
                                        <?php
                                        if (@$_POST["ara"]) {
                                            $eposta = $_POST["eposta"];
                                            $query = $dbh->prepare('SELECT email, hash FROM users WHERE email = ?');
                                            $query->execute([$eposta]);
                                            if ($query->rowCount()) {
                                                $row = $query->fetch(PDO::FETCH_ASSOC);


                                                error_reporting(E_STRICT);

                                                date_default_timezone_set('Europe/Istanbul');

                                                require_once('class.phpmailer.php');
                                                $mail = new PHPMailer();

                                                $mail->IsSMTP(); // telling the class to use SMTP
                                                $mail->SMTPDebug = 0;                     // enables SMTP debug information (for testing)
                                                // 1 = errors and messages
                                                // 2 = messages only
                                                $mail->SMTPAuth = true;                  // enable SMTP authentication
                                                $mail->Host = "friend.guzelhosting.com"; // sets the SMTP server
                                                $mail->SMTPSecure = "";
                                                $mail->Port = 587;                    // set the SMTP port for the GMAIL server
                                                $mail->Username = "iseser@iseser.com"; // SMTP account username
                                                $mail->Password = "r(A9O#wYz^T!";        // SMTP account password
                                                $mail->CharSet = "utf-8";

                                                $mail->SetFrom("iseser@iseser.com", 'ISESER refresh password');

                                                $mail->AddReplyTo("iseser@iseser.com", "ISESER refresh password");

                                                $mail->Subject = "ISESER refresh password";

                                                $mail->AltBody = "ISESER refresh password"; // optional, comment out and test


                                                $body = 'Click on the link to refresh your password <a href="https://iseser.com/user/forget-password.php?yenile=' . $row["hash"] . '">https://iseser.com/user/forget-password.php?yenile=' . $row["hash"] . '</a>' .
                                                    '<br/> iseser.com <br/>';

                                                $mail->MsgHTML($body);

                                                $mail->AddAddress($eposta, "iseser@iseser.com");

                                                if (!$mail->Send()) {
                                                    echo "Mailer Error: " . $mail->ErrorInfo;
                                                } else {
                                                    echo $msg = 'The link to renew the password was sent to your mail address.';
                                                }
                                            } else {
                                                echo 'No Email Address';
                                            }
                                        }

                                        if (isset($_GET["yenile"])) {
                                            $hash = $_GET["yenile"];
                                            $query = $dbh->prepare('SELECT * FROM users WHERE hash = ?');
                                            $query->execute([$hash]);
                                            if ($query->rowCount()) {
                                                if (@$_POST["sifreYenile"]) {
                                                    $sifre = $_POST["sifre"];
                                                    $yeniHash = createHash();
                                                    $query = $dbh->prepare('UPDATE users SET password = ?, hash = ? WHERE hash = ?');
                                                    $query->execute([md5($sifre), $yeniHash, $hash]);
                                                    header("Location:https://iseser.com/user/");
                                                }
                                                ?>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Enter New Password:</label>
                                                    <div class="col-sm-8">
                                                        <input type="password" class="form-control" name="sifre">
                                                    </div>
                                                </div>
                                                <div class="hr-dashed"></div>
                                                <div class="form-group">
                                                    <div class="col-sm-8 col-sm-offset-4">
                                                        <input class="btn btn-primary" name="sifreYenile"
                                                               value="Refresh Password"
                                                               type="submit">
                                                    </div>
                                                </div>

                                                <?php
                                            } else {
                                                header("Location:https://iseser.com/user/");
                                            }
                                        } else {
                                            ?>


                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">E-Mail Address:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="eposta">
                                                </div>
                                            </div>
                                            <div class="hr-dashed"></div>
                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-4">
                                                    <input class="btn btn-primary" name="ara" value="Send New Password"
                                                           type="submit">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Loading Scripts -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/Chart.min.js"></script>
<script src="js/fileinput.js"></script>
<script src="js/chartData.js"></script>
<script src="js/main.js"></script>

</body>
</html>