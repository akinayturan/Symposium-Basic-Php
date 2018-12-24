<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $sql = "DELETE FROM articles WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Data Deleted successfully";
    }

    if (isset($_REQUEST['usunconfirm'])) {
        $aeid = intval($_GET['usunconfirm']);
        $memstatus = 1;
        $sql = "UPDATE articles SET u_status=:u_status WHERE  id=:aeid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':u_status', $memstatus, PDO::PARAM_STR);
        $query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
        $query->execute();
        $msg = "Changes Sucessfully";
    }

    if (isset($_REQUEST['usconfirm'])) {
        $aeid = intval($_GET['usconfirm']);
        $memstatus = 0;
        $sql = "UPDATE articles SET u_status=:u_status WHERE  id=:aeid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':u_status', $memstatus, PDO::PARAM_STR);
        $query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
        $query->execute();
        $msg = "Changes Sucessfully";
    }


    if (isset($_REQUEST['psunconfirm'])) {
        $aeid = intval($_GET['psunconfirm']);
        $memstatus = 1;
        $sql = "UPDATE articles SET p_status=:p_status WHERE  id=:aeid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':p_status', $memstatus, PDO::PARAM_STR);
        $query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
        $query->execute();
        $msg = "Changes Sucessfully";
    }

    if (isset($_REQUEST['psconfirm'])) {
        $aeid = intval($_GET['psconfirm']);
        $memstatus = 0;
        $sql = "UPDATE articles SET p_status=:p_status WHERE  id=:aeid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':p_status', $memstatus, PDO::PARAM_STR);
        $query->bindParam(':aeid', $aeid, PDO::PARAM_STR);
        $query->execute();
        $msg = "Changes Sucessfully";
    }

    $reciver = 'Admin';
    $sql = "SELECT * FROM  articles where reciver = (:reciver)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':reciver', $reciver, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
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

        <title>Manage Articlesa</title>

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

                        <h2 class="page-title">Manage Articlesa</h2>

                        <!-- Zero Configuration Table -->
                        <div class="panel panel-default">
                            <div class="panel-heading">List Users</div>
                            <div class="panel-body">
                                <?php if ($error) { ?>
                                    <div class="errorWrap"
                                         id="msgshow"><?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?>
                                    <div class="succWrap"
                                         id="msgshow"><?php echo htmlentities($msg); ?> </div><?php } ?>
                                <table id="zctb" class="display table table-striped table-bordered table-hover"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Email</th>
                                        <th>Title</th>
                                        <th>Article</th>
                                        <th>paptype</th>
                                        <th>Attachment</th>
                                        <th>Action</th>
                                        <th>U. Status</th>
                                        <th>P. Status</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <?php
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) { ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt); ?></td>
                                                <td><?php echo htmlentities($result->sender); ?></td>
                                                <td><?php echo htmlentities($result->title); ?></td>
                                                <td><?php echo htmlentities($result->articlesdata); ?></td>
                                                <td><?php echo htmlentities($result->paptype); ?></td>


                                                <td>
                                                    <a href="../articles/<?php echo htmlentities($result->attachment); ?>"><i
                                                                class="fa fa-folder"></i>&nbsp; Download</a>
                                                </td>
                                                <td>
                                                    <a href="articles.php?del=<?php echo $result->id; ?>&name=<?php echo htmlentities($result->title); ?>"
                                                       onclick="return confirm('Do you want to Delete');"><i
                                                                class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
                                                </td>
                                                <td>

                                                    <?php if ($result->u_status == 1) {
                                                        ?>
                                                        <a href="articles.php?usconfirm=<?php echo htmlentities($result->id); ?>"
                                                           onclick="return confirm('Do you really want to Un-Confirm the Articles')">Confirmed
                                                            <i class="fa fa-check-circle"></i></a>
                                                    <?php } else { ?>
                                                        <a href="articles.php?usunconfirm=<?php echo htmlentities($result->id); ?>"
                                                           onclick="return confirm('Do you really want to Confirm the Articles')">Un-Confirmed
                                                            <i class="fa fa-times-circle"></i></a>
                                                    <?php } ?>
                                                </td>
                                                <td>

                                                    <?php if ($result->p_status == 1) {
                                                        ?>
                                                        <a href="articles.php?psconfirm=<?php echo htmlentities($result->id); ?>"
                                                           onclick="return confirm('Do you really want to Un-Confirm the Payment')">Confirmed
                                                            <i class="fa fa-check-circle"></i></a>
                                                    <?php } else { ?>
                                                        <a href="articles.php?psunconfirm=<?php echo htmlentities($result->id); ?>"
                                                           onclick="return confirm('Do you really want to Confirm the Payment')">Un-Confirmed
                                                            <i class="fa fa-times-circle"></i></a>
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
