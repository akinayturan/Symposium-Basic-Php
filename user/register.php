<?php
include('includes/config.php');
if (isset($_POST['submit'])) {

    $file = $_FILES['image']['name'];
    $file_loc = $_FILES['image']['tmp_name'];
    $folder = "images/";
    $new_file_name = strtolower($file);
    $final_file = str_replace(' ', '-', $new_file_name);

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
    $gender = $_POST['gender'];
    $mobileno = $_POST['mobileno'];

    if (move_uploaded_file($file_loc, $folder . $final_file)) {
        $image = $final_file;
    }
    $notitype = 'Create Account';
    $reciver = 'Admin';
    $sender = $email;

    $sqlnoti = "insert into notification (notiuser,notireciver,notitype) values (:notiuser,:notireciver,:notitype)";
    $querynoti = $dbh->prepare($sqlnoti);
    $querynoti->bindParam(':notiuser', $sender, PDO::PARAM_STR);
    $querynoti->bindParam(':notireciver', $reciver, PDO::PARAM_STR);
    $querynoti->bindParam(':notitype', $notitype, PDO::PARAM_STR);
    $querynoti->execute();

    $sql = "INSERT INTO users(name,surname,titlecon,typecon,institution,faculty,department,address, email, password, gender, mobile, image) VALUES(:name, :surname, :titlecon, :typecon, :institution, :faculty, :department, :address, :email, :password, :gender, :mobileno, :image)";
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
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
    $query->bindParam(':image', $image, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
        echo "<script type='text/javascript'>alert('Registration Sucessfull!');</script>";
        echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
    } else {
        $error = "Something went wrong. Please try again";
    }

}
?>

<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">


    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript">

        function validate() {
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

    </script>
</head>
<body>
<div class="login-page bk-img">
    <div class="form-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center text-bold mt-2x">Register</h1>
                    <div class="hr-dashed"></div>
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
                                    <select id="titlecon" name="titlecon" data-style="btn-danger"
                                            class="selectpicker form-control" title="*Title" required>
                                        <option>Prof. Dr.</option>
                                        <option>Assoc. Prof. Dr.</option>
                                        <option>Assist. Prof. Dr.</option>
                                        <option>Lecturer</option>
                                        <option>Lecturer PhD.</option>
                                        <option>Assistant</option>
                                        <option>Assistant PhD.</option>
                                        <option>PhD.</option>
                                        <option>Lecturer</option>
                                        <option>Expert</option>
                                        <option>Graduate Student</option>
                                        <option>PhD. Student</option>
                                        <option>Undergraduate Student</option>
                                        <option>Engineer</option>
                                        <option>Other</option>
                                    </select>

                                </div>
                                <label class="col-sm-1 control-label">Type Of Congress Application<span
                                            style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <select id="typecon" name="typecon" data-style="btn-danger"
                                            class="selectpicker form-control" title="*Type Of Congress Application"
                                            required>
                                        <option value='1'>Author</option>
                                        <option value='2'>Guest</option>
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
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <label class="col-sm-1 control-label">Phone<span style="color:red">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number" name="mobileno" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-1 control-label">Avatar</label>
                                <div class="col-sm-5">
                                    <div><input type="file" name="image" class="form-control"></div>
                                </div>
                            </div>

                            <br>
                            <button class="btn btn-primary" name="submit" type="submit">Register</button>
                        </form>
                        <br>
                        <br>
                        <p>Already Have Account? <a href="index.php">Signin</a></p>
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