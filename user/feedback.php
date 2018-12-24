<?php
session_start();
error_reporting(0);
include('admin/includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['submit'])) {
        $file = $_FILES['attachment']['name'];
        $file_loc = $_FILES['attachment']['tmp_name'];
        $folder = "feedback/";
        $new_file_name = strtolower($file);
        $final_file = str_replace(' ', '-', $new_file_name);

        $title = $_POST['title'];
        $description = $_POST['description'];
        $user = $_SESSION['alogin'];
        $reciver = 'Admin';
        $notitype = 'Send Feedback';
        $attachment = ' ';

        if (move_uploaded_file($file_loc, $folder . $final_file)) {
            $attachment = $final_file;
        }
        $notireciver = 'Admin';
        $sqlnoti = "insert into notification (notiuser,notireciver,notitype) values (:notiuser,:notireciver,:notitype)";
        $querynoti = $dbh->prepare($sqlnoti);
        $querynoti->bindParam(':notiuser', $user, PDO::PARAM_STR);
        $querynoti->bindParam(':notireciver', $notireciver, PDO::PARAM_STR);
        $querynoti->bindParam(':notitype', $notitype, PDO::PARAM_STR);
        $querynoti->execute();

        $sql = "insert into feedback (sender, reciver, title,feedbackdata,attachment) values (:user,:reciver,:title,:description,:attachment)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->bindParam(':reciver', $reciver, PDO::PARAM_STR);
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':attachment', $attachment, PDO::PARAM_STR);
        $query->execute();
        $msg = "Feedback Send";
    }


    $email = $_SESSION['alogin'];
    $sql = "SELECT * from users where email = (:email);";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $cnt = 1;


    $reciver = $_SESSION['alogin'];
    $sql = "SELECT * from feedback where reciver = (:reciver) OR sender  = (:reciver)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':reciver', $reciver, PDO::PARAM_STR);
    $query->execute();
    $resultmessages = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    ?>
    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <title>ISESER User Feedback</title>
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

        <script type="text/javascript" src="../vendor/countries.js"></script>
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
                var extensions = new Array("doc", "docx");
                var attachment_file = document.regform.attachment.value;
                var attachment_length = document.regform.attachment.value.length;
                var pos = attachment_file.lastIndexOf('.') + 1;
                var ext = attachment_file.substring(pos, attachment_length);
                var final_ext = ext.toLowerCase();
                for (i = 0; i < extensions.length; i++) {
                    if (extensions[i] == final_ext) {
                        return true;
                    }
                }
                alert("Attachment Extension Not Valid (Use doc,docx)");
                return false;
            }

        </script>
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
                                <h2>Feedback</h2>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Send New Feedback</div>
                                    <?php if ($error) { ?>
                                        <div class="errorWrap"><strong>ERROR</strong>
                                        :<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?>
                                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
                                        </div><?php } ?>

                                    <div class="panel-body">


                                        <form method="post" class="form-horizontal" enctype="multipart/form-data">

                                            <div class="form-group">
                                                <input type="hidden" name="user"
                                                       value="<?php echo htmlentities($result->email); ?>">
                                                <label class="col-sm-2 control-label">Title<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="title" class="form-control" required>
                                                </div>

                                                <label class="col-sm-2 control-label">Attachment<span
                                                            style="color:red"></span></label>
                                                <div class="col-sm-4">
                                                    <input type="file" name="attachment" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Description<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" rows="5"
                                                              name="description"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <button class="btn btn-primary" name="submit" type="submit">Send
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">Sended Before Feedback</div>


                                    <div class="panel-body">

                                        <table id="zctb" class="display table table-striped table-bordered table-hover"
                                               cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User</th>
                                                <th>Message</th>
                                                <th>Attachment</th>
                                            </tr>
                                            </thead>

                                            <tbody>

                                            <?php
                                            if ($query->rowCount() > 0) {
                                                foreach ($resultmessages as $messages) { ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><?php echo htmlentities($messages->sender); ?></td>
                                                        <td><?php echo htmlentities($messages->feedbackdata); ?></td>
                                                        <td>
                                                            <?php if (isset($messages->attachment)){ ?>
                                                            <a target="_blank"
                                                               href="feedback/<?php echo htmlspecialchars($messages->attachment); ?>"><i
                                                                        class="fa fa-folder"></i> &nbsp;Download</a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php $cnt = $cnt + 1;
                                                }
                                            } ?>

                                            </tbody>
                                        </table>


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