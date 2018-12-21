<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
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
        $mobileno = $_POST['mobile'];
        $idedit = $_POST['editid'];
        $image = $_POST['image'];

        if (move_uploaded_file($file_loc, $folder . $final_file)) {
            $image = $final_file;
        }

        $sql = "UPDATE users SET name=(:name), email=(:email), mobile=(:mobileno), department=(:department), Image=(:image) WHERE id=(:idedit)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':surname', $name, PDO::PARAM_STR);
        $query->bindParam(':titlecon', $name, PDO::PARAM_STR);
        $query->bindParam(':typecon', $name, PDO::PARAM_STR);
        $query->bindParam(':institution', $name, PDO::PARAM_STR);
        $query->bindParam(':faculty', $name, PDO::PARAM_STR);
        $query->bindParam(':department', $department, PDO::PARAM_STR);
        $query->bindParam(':address', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->bindParam(':image', $image, PDO::PARAM_STR);
        $query->bindParam(':idedit', $idedit, PDO::PARAM_STR);
        $query->execute();
        $msg = "Information Updated Successfully";
    }
    ?>

    <?php
    $email = $_SESSION['alogin'];
    $sql = "SELECT * from users where email = (:email);";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $cnt = 1;
    ?>
    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="theme-color" content="#3e454c">

        <title>Edit Profile</title>

        <!-- Font awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Sandstone Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Bootstrap Datatables -->
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <!-- Bootstrap social button library -->
        <link rel="stylesheet" href="css/bootstrap-social.css">
        <!-- Bootstrap select -->
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <!-- Bootstrap file input -->
        <link rel="stylesheet" href="css/fileinput.min.css">
        <!-- Awesome Bootstrap checkbox -->
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <!-- Admin Stye -->
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


    </head>

    <body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/leftbar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><?php echo htmlentities($_SESSION['alogin']); ?></div>
                                    <?php if ($error) { ?>
                                        <div class="errorWrap"><strong>ERROR</strong>
                                        :<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?>
                                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
                                        </div><?php } ?>

                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal" enctype="multipart/form-data">

                                            <div class="form-group">

                                                <label class="col-sm-2 control-label">Avatar<span
                                                            style="color:red">*</span></label>

                                                <div class="col-sm-4 text-center">
                                                    <input type="file" name="image" class="form-control">
                                                    <input type="hidden" name="image" class="form-control"
                                                           value="<?php echo htmlentities($result->image); ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Name<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="name" class="form-control" required
                                                           value="<?php echo htmlentities($result->name); ?>">
                                                </div>

                                                <label class="col-sm-2 control-label">Surname<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="surname" class="form-control" required
                                                           value="<?php echo htmlentities($result->surname); ?>">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">titlecon<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="titlecon" class="form-control" required
                                                           value="<?php echo htmlentities($result->titlecon); ?>">
                                                </div>

                                                <label class="col-sm-2 control-label">typecon<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="typecon" class="form-control" required
                                                           value="<?php echo htmlentities($result->typecon); ?>">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">institution<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="institution" class="form-control" required
                                                           value="<?php echo htmlentities($result->institution); ?>">
                                                </div>

                                                <label class="col-sm-2 control-label">faculty<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="faculty" class="form-control" required
                                                           value="<?php echo htmlentities($result->faculty); ?>">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">department<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="department" class="form-control" required
                                                           value="<?php echo htmlentities($result->department); ?>">
                                                </div>

                                                <label class="col-sm-2 control-label">address<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="address" class="form-control" required
                                                           value="<?php echo htmlentities($result->address); ?>">
                                                </div>
                                            </div>


                                            <div class="form-group">

                                                <label class="col-sm-2 control-label">email<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="email" name="email" class="form-control" required
                                                           value="<?php echo htmlentities($result->email); ?>">
                                                </div>

                                                <label class="col-sm-2 control-label">Mobile<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="mobile" class="form-control" required
                                                           value="<?php echo htmlentities($result->mobile); ?>">
                                                </div>
                                            </div>

                                            <input type="hidden" name="editid" class="form-control" required
                                                   value="<?php echo htmlentities($result->id); ?>">

                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <button class="btn btn-primary" name="submit" type="submit">Save
                                                        Changes
                                                    </button>
                                                </div>
                                            </div>

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
    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout(function () {
                $('.succWrap').slideUp("slow");
            }, 3000);
        });
    </script>
    </body>
    </html>
<?php } ?>