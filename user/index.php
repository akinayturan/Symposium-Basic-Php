<?php
session_start();
include('admin/includes/config.php');
if(isset($_POST['login']))
{
$status='1';
$email=$_POST['username'];
$password=md5($_POST['password']);
$sql ="SELECT email,password FROM users WHERE email=:email and password=:password and status=(:status)";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> bindParam(':status', $status, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
$_SESSION['alogin']=$_POST['username'];
echo "<script type='text/javascript'> document.location = 'profile.php'; </script>";
} else{
  
  echo "<script>alert('Invalid Details Or Account Not Confirmed');</script>";

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

</head>

<body>

<div class="brand clearfix">

    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">&nbsp;<a href="/">HOME PAGE</a></h4>
    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">|</h4>
    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">&nbsp;<a href="/user/">SIGN IN</a></h4>
    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">|</h4>

    <h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px">&nbsp;<a href="/user/signup.php">SIGN UP</a> </h4>
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

                    <div class="hr-dashed"></div>
                    <div class="well row pt-2x pb-3x bk-light text-center">
					<div class="col-md-6 col-md-offset-3">
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
								<form method="post">
									<label for="" class="text-uppercase text-sm">Email</label>
									<input type="text" placeholder="Email" name="username" class="form-control mb" required>

									<label for="" class="text-uppercase text-sm">Password</label>
									<input type="password" placeholder="Password" name="password" class="form-control mb" required>
									<button class="btn btn-primary btn-block" name="login" type="submit">SIGNIN</button>
								</form>
								<br>
                                <p><a class="btn btn-info btn-block"  href="signup.php" >SIGNUP</a></p>
                                <p>If you have forgotten your password, please send an e-mail to <a href="mailto:iseser@iseser.com">iseser@iseser.com</a> .</p>
							</div>
						</div>
					</div>
                    </div></div>
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