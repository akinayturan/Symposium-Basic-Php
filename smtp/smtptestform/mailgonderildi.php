<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Mail Gonder</title>
 
</head>
<body>

<?php
$body = 'Your membership has been realized successfully. <br/>' .
    'Dear ' . $titlecon . $name . $surname . ' <br/>' .
    'Login Name = ' . $email . ' <br/>' .
    'Password = ' . $password . ' <br/>' .
    'Phone = ' . $mobileno . ' <br/>' .
    'iseser.com <br/>';
$body = iconv("ISO-8859-9", "UTF-8", $body);
//error_reporting(E_ALL);
error_reporting(E_STRICT);

date_default_timezone_set('Europe/Istanbul');

require_once('class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

//$body             = file_get_contents('contents.html');
//$body             = preg_replace('/[\]/','',$body);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "friend.guzelhosting.com"; // sets the SMTP server
$mail->SMTPSecure = "";
$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
$mail->Username   = "iseser@iseser.com"; // SMTP account username
$mail->Password   = "r(A9O#wYz^T!";        // SMTP account password

$mail->SetFrom("iseser@iseser.com", 'ISESER NEW Registration');

$mail->AddReplyTo("iseser@iseser.com","ISESER NEW Registration");

$mail->Subject    = "ISESER NEW Registration";

$mail->AltBody    = "ISESER NEW Registration"; // optional, comment out and test

$mail->MsgHTML($body);

$address = "iseser@iseser.com";
$mail->AddAddress($address, "iseser@iseser.com");


if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "<center><h1>Mail Gonderildi</h1> Y�nlendiriliyorsunuz.</center>";
}

?>
</body>
</html>
