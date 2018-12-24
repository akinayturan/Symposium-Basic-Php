<?php
session_start();
error_reporting(0);
include('admin/includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_GET['del']) && isset($_GET['name'])) {
        $id = $_GET['del'];
        $name = $_GET['name'];

        $sql = "delete from articles WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();

        try {
            $sql2 = "insert into deletedarticles (articlename) values (:articlename)";
            $query2 = $dbh->prepare($sql2);
            $query2->bindParam(':articlename', $name, PDO::PARAM_STR);
            $query2->execute();
            $msg = "Data Deleted successfully";
        } catch (PDOException $e) {
            var_dump($e);
            exit();
        }
    }
    if (isset($_POST['submit'])) {
        $file = $_FILES['attachment']['name'];
        $file_loc = $_FILES['attachment']['tmp_name'];
        $folder = "articles/";
        $new_file_name = strtolower($file);
        $final_file = str_replace(' ', '-', date('Y-m-d-H-i-s') . "_" . $new_file_name);

        $title = $_POST['title'];
        $paptype = $_POST['paptype'];
        $ptype = $_POST['ptype'];
        $articlesdata = $_POST['articlesdata'];
        $user = $_SESSION['alogin'];
        $reciver = 'Admin';
        $notitype = 'Send Article';
        $attachment = ' ';

        if (move_uploaded_file($file_loc, $folder . $final_file)) {
            $attachment = $final_file;
        }
        $notireciver = 'Admin';

        try {
            $sqlnoti = "insert into notification (notiuser,notireciver,notitype) values (:notiuser,:notireciver,:notitype)";
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $querynoti = $dbh->prepare($sqlnoti);
            $querynoti->bindParam(':notiuser', $user, PDO::PARAM_STR);
            $querynoti->bindParam(':notireciver', $notireciver, PDO::PARAM_STR);
            $querynoti->bindParam(':notitype', $notitype, PDO::PARAM_STR);
            $querynoti->execute();
            $sql = "insert into articles (sender, reciver, title, paptype, ptype, articlesdata,attachment) values (:user, :reciver, :title, :paptype, :ptype, :articlesdata,:attachment)";
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $dbh->prepare($sql);
            $query->bindParam(':user', $user, PDO::PARAM_STR);
            $query->bindParam(':reciver', $reciver, PDO::PARAM_STR);
            $query->bindParam(':title', $title, PDO::PARAM_STR);

            $query->bindParam(':paptype', $paptype, PDO::PARAM_STR);
            $query->bindParam(':ptype', $ptype, PDO::PARAM_STR);
            $query->bindParam(':articlesdata', $articlesdata, PDO::PARAM_STR);
            $query->bindParam(':attachment', $attachment, PDO::PARAM_STR);
            $query->execute();
            $msg = "Article Send";
        } catch (PDOException $e) {
            $error = 'Unsuccesfully Insert Article :' . $e->getMessage();
        }
    }

    $email = $_SESSION['alogin'];
    $sql = "SELECT * from users where email = (:email);";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    $cnt = 1;

    $email = $_SESSION['alogin'];
    $sql = "SELECT * FROM `articles` WHERE `sender` = (:email);";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $resultArticle = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    ?>
    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <title>ISESER User Articles</title>

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
                                <h2>Articles</h2>
                                <div class="panel panel-default">
                                    <div class="panel-heading">Send New Article</div>
                                    <?php if ($error) { ?>
                                        <div class="errorWrap"><strong>ERROR</strong>
                                        :<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?>
                                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
                                        </div><?php } ?>

                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal" enctype="multipart/form-data"
                                              onSubmit="return validate();" name="regform">

                                            <div class="form-group">
                                                <input type="hidden" name="user"
                                                       value="<?php echo htmlentities($result->email); ?>">
                                                <label class="col-sm-2 control-label">Title of Paper:<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="title" class="form-control" required>
                                                </div>

                                            </div>

                                            <div class="form-group">

                                                <label class="col-sm-2 control-label">Topic of Paper<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="articlesdata" class="form-control"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <label class="col-sm-2 control-label">Paper Type<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <select name="paptype" class="form-control" required>
                                                        <option value="">Select</option>
                                                        <option value="Abstract">Abstract</option>
                                                        <option value="Full Paper">Full Paper</option>
                                                    </select>
                                                </div>

                                                <label class="col-sm-2 control-label">Presentation Type<span
                                                            style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <select name="ptype" class="form-control" required>
                                                        <option value="">Select</option>
                                                        <option value="Oral">Oral</option>
                                                        <option value="Poster">Poster</option>
                                                    </select>
                                                </div>

                                            </div>

                                            <div class="form-group">

                                                <label class="col-sm-2 control-label">Attachment<span
                                                            style="color:red"></span></label>
                                                <div class="col-sm-10">
                                                    <input type="file" name="attachment" class="form-control" required>
                                                </div>


                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <button class="btn btn-primary col-sm-6" name="submit"
                                                            type="submit">Send
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">Sended Before Article</div>


                                    <div class="panel-body">

                                        <table id="zctb" class="display table table-striped table-bordered table-hover"
                                               cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Title of Abstract:</th>
                                                <th>Topic of article</th>
                                                <th>Pre. Type</th>
                                                <th>Pap. Type</th>
                                                <th>Attachment</th>
                                                <th>Status</th>
                                                <th>Payment</th>
                                                <th>Act</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php
                                            foreach ($resultArticle as $row) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row->title) ?></td>
                                                    <td><?php echo htmlspecialchars($row->articlesdata); ?></td>
                                                    <td><?php echo htmlspecialchars($row->ptype); ?></td>
                                                    <td><?php echo htmlspecialchars($row->paptype); ?></td>
                                                    <td>
                                                        <a target="_blank"
                                                           href="articles/<?php echo htmlspecialchars($row->attachment); ?>"><i
                                                                    class="fa fa-folder"></i> &nbsp;Download</a></td>
                                                    <td><?php echo htmlspecialchars($row->u_status); ?></td>
                                                    <td><?php echo htmlspecialchars($row->p_status); ?></td>
                                                    <td>
                                                        <a href="articles.php?del=<?php echo $row->id; ?>&name=<?php echo htmlentities($row->title); ?>"
                                                           onclick="return confirm('Do you want to Delete');"><i
                                                                    class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
                                                    </td>
                                                </tr>
                                            <?php } ?>
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