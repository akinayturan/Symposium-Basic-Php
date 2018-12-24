<?php
include('admin/includes/config.php');

function createHash($uzunluk = 16)
{
    $karakterler = 'qwertyuopasdfghjklizxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    return substr(str_shuffle($karakterler), 0, $uzunluk);
}

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $titlecon = $_POST['titlecon'];
    $typecon = $_POST['typecon'];
    $institution = $_POST['institution'];
    $faculty = $_POST['faculty'];
    $department = $_POST['department'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $hash = createHash();
    $gender = $_POST['gender'];
    $mobileno = $_POST['mobileno'];

    $file = $_FILES['image']['name'];

    $file_loc = $_FILES['image']['tmp_name'];
    $folder = "images/";
    $new_file_name = strtolower($file);

    $final_file = str_replace(' ', '-', date('Y-m-d-H-i-s') . $new_file_name);

    if (move_uploaded_file($file_loc, $folder . $final_file)) {
        $image = $final_file;

    } else {
        $image = "demo.jpg";
    }

    $notitype = 'Create Account';
    $reciver = 'Admin';
    $sender = $email;
    try {
        $sqlnoti = "INSERT INTO notification (notiuser,notireciver,notitype) VALUES (:notiuser,:notireciver,:notitype)";
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $querynoti = $dbh->prepare($sqlnoti);

        $querynoti->bindParam(':notiuser', $sender, PDO::PARAM_STR);
        $querynoti->bindParam(':notireciver', $reciver, PDO::PARAM_STR);
        $querynoti->bindParam(':notitype', $notitype, PDO::PARAM_STR);
        $querynoti->execute();

        $sql = "INSERT INTO users(name,surname,titlecon,typecon,institution,faculty,department,address, email, password, hash, gender, mobile, image) VALUES(:name, :surname, :titlecon, :typecon, :institution, :faculty, :department, :address, :email, :password, :hash, :gender, :mobileno, :image)";
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':surname', $surname, PDO::PARAM_STR);
        $query->bindParam(':titlecon', $titlecon, PDO::PARAM_STR);
        $query->bindParam(':typecon', $typecon, PDO::PARAM_STR);
        $query->bindParam(':institution', $institution, PDO::PARAM_STR);
        $query->bindParam(':faculty', $faculty, PDO::PARAM_STR);
        $query->bindParam(':department', $department, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':hash', $hash, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->bindParam(':image', $image, PDO::PARAM_STR);
        $query->execute();


        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {

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

            $mail->SetFrom("iseser@iseser.com", 'ISESER NEW Registration');

            $mail->AddReplyTo("iseser@iseser.com", "ISESER NEW Registration");

            $mail->Subject = "ISESER NEW Registration";

            $mail->AltBody = "ISESER NEW Registration"; // optional, comment out and test


            $body = 'Your membership has been realized successfully. <br/>' .
                'Dear ' . $titlecon . $name . $surname . ' <br/>' .
                'LoginID = ' . $email . ' <br/>' .
                'Password = ' . $_POST['password'] . ' <br/>' .
                'Phone = ' . $mobileno . ' <br/>' .
                'iseser.com <br/>';

            $mail->MsgHTML($body);

            $address = "iseser@iseser.com";
            $mail->AddAddress($email, "iseser@iseser.com");
            $mail->AddAddress($address, "ISESER NEW Registration");

            if (!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo $msg = 'Registration Sucessfull!';
            }
            echo $msg = 'Registration Sucessfull!';
            echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
        } else {
            $error = "Something went wrong. Please try again";
        }
    } catch (PDOException $e) {
        $error = 'Unsuccesfully Insert User :' . $e->getMessage();
    }
}

$sql = "SELECT value from parameters where type ='typecon';";
$query = $dbh->prepare($sql);
$query->execute();
$typecons = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT value from parameters where type ='titlecon';";
$query = $dbh->prepare($sql);
$query->execute();
$titlecons = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT value from parameters where type ='gender';";
$query = $dbh->prepare($sql);
$query->execute();
$genders = $query->fetchAll(PDO::FETCH_ASSOC);

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
                    <div class="hr-dashed"></div>
                    <?php if ($error) { ?>
                        <div class="errorWrap"><strong>ERROR</strong>
                        :<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?>
                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
                        </div><?php } ?>
                    <div class="well row pt-2x pb-3x bk-light text-center">
                        <form method="post" class="form-horizontal" enctype="multipart/form-data" name="regform"
                              onSubmit="return validate();">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Name<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <label class="col-sm-1 control-label">Surname<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="surname" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">Title<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <select name="titlecon" class="form-control" required>
                                        <option></option>
                                        <?php
                                        foreach ($titlecons as $titlecon) {
                                            foreach ($titlecon as $value) {
                                                echo "<option value='" . $value . "'> " . $value . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>
                                <label class="col-sm-1 control-label">Type<span
                                            style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <select name="typecon" class="form-control" required>
                                        <option></option>
                                        <?php
                                        foreach ($typecons as $typecon) {
                                            foreach ($typecon as $value) {
                                                echo "<option value='" . $value . "'> " . $value . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">Institution<span
                                            style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="institution" class="form-control" required>
                                </div>
                                <label class="col-sm-1 control-label">Faculty<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="faculty" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">Department<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="department" class="form-control" required>
                                </div>
                                <label class="col-sm-1 control-label">Address<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="address" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-sm-1 control-label">Email<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" name="email" class="form-control" required>
                                </div>

                                <label class="col-sm-1 control-label">Password<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="password" name="password" class="form-control" id="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">Gender<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <select name="gender" class="form-control" required>
                                        <option></option>
                                        <?php
                                        foreach ($genders as $gender) {
                                            foreach ($gender as $value) {
                                                echo "<option value='" . $value . "'> " . $value . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>

                                </div>

                                <label class="col-sm-1 control-label">Phone</label>
                                <div class="col-sm-5">
                                    <input type="number" name="mobileno" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">Photo</label>
                                <div class="col-sm-5">
                                    <div><input type="file" name="image" value="poster2.jpg" class="form-control"></div>
                                </div>
                            </div>

                            <br>
                            <div class="form-group">
                                <div class="col-md-4"></div>
                                <button class="btn btn-primary col-md-4" name="submit" type="submit">Register</button>
                                <div class="col-md-4"></div>

                            </div>
                            <div class="form-group">
                                <br>
                                <p>Already Have Account? <a href="index.php">Signin</a></p>
                            </div>
                        </form>
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