<?php 
 include('./connectionData.txt');
 $mysqli = new mysqli($server, $user, $pass, $dbname, $port)
 or die('Error connecting');
 ?>
<?php
	if (isset($_POST['login'])) {
		$username=$_POST['username'];
		$password=$_POST['password'];
		//check input user information is valid
		if(isset($username) && isset($password) && !empty($username) && !empty($password))
		{
			$stmt = $mysqli -> prepare("SELECT * FROM user WHERE username ='$username' AND password = '$password';");
			$stmt->execute();
			$user_id=null;
			$username=null;
			$password=null;
			$u_email=null;
			$u_phonenum=null; 
			$stmt->bind_result($user_id,$username,$password,$u_email,$u_phonenum);
			while($stmt->fetch())printf('',$user_id);	
			//if user exists, redirect to index page
			
			if($stmt->num_rows==1)
			{
				session_start();
				$_SESSION['auth']='true';
				$_SESSION['username']=$username;
				$_SESSION['password']=$password;
				header('location:goals.php');
			}
			else{
				echo "<script type='text/javascript'> alert('Wrong username or password...') </script>";
			}
		}			
    } else {
		$username=$_POST['create_username'];
		$password=$_POST['create_password'];
		$email=$_POST['create_email'];
		$number=$_POST['create_number'];
		//check the user information is valid when creating
		if(isset($username) && isset($password) && !empty($username) && !empty($password) && isset($email) && isset($number) && !empty($email) && !empty($number)){
			$sql = "INSERT INTO user (username, password, email, phone_num) VALUES ('{$username}','{$password}','{$email}','{$number}');";
			header("location:{$sql}");
			// after successfull create the user, process redirection
			if ($mysqli->query($sql) === TRUE){
				header('location:goals.php');
			} else {
				echo "<h2>Error Inserting Values into Database</h2>";
			}
		}
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


    <title>Peel Pages</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/agency.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/styles.css">

</head>

<body id="page-top" class="index">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_CN/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="././goals.php"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a class="page-scroll" href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">Home</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#portfolio">Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="portfolio" class="bg-light-gray">

		<div class="row">
			<div class="col-lg-12 text-center">
				<h1 style="color: white" class="section-heading">Welcome <?php echo $username;?>!</h1>
			</div>
		</div>
		<hr style="border-top: 2px solid #fff;">        
        <P></p>

		<div class="container">
			<div class="row">
				<div class="span12">
					<div class="" id="loginModal">
					  <div class="modal-body">
						<div class="well">
						  <ul class="nav nav-tabs">
							<li class="active"><a href="#login" data-toggle="tab">Login</a></li>
							<li><a href="#create" data-toggle="tab">Create Account</a></li>
						  </ul>
						  <div id="myTabContent" class="tab-content">
							<div class="tab-pane active in" id="login">
							  <form class="form-horizontal" method="POST">
								<fieldset style="margin-top: 20px; margin-left: 30px; margin-bottom: 20px;">
								  <div id="legend">
									<legend class="">Login</legend>
								  </div>    
								  <div class="control-group">
									<!-- Username -->
									<label class="control-label"  for="username">Username</label>
									<div class="controls">
									  <input type="text" id="username" name="username" placeholder="" class="input-xlarge">
									</div>
								  </div>
			 
								  <div class="control-group">
									<!-- Password-->
									<label class="control-label" for="password">Password</label>
									<div class="controls">
									  <input type="password" id="password" name="password" placeholder="" class="input-xlarge">
									</div>
								  </div>
			 
								  <div class="control-group" style="margin-top:14px;">
									<!-- Button -->
									<div class="controls">
									  <input name="login" class="btn btn-primary" type="submit" value="Login">
									</div>
								  </div>
								</fieldset>
							  </form>                
							</div>
							<div class="tab-pane fade" id="create">
                              <div id="legend" style="margin-top: 20px;">
                                <legend class="">Create an Account</legend>
                              </div>    
							  <form id="tab" method="POST" style="margin-left:18px; margin-bottom:20px;">
								<label>Username</label>
                                <br>
								<input name="create_username" type="text" class="input-xlarge" required>
                                <br>
								<label>Password</label>
                                <br>
								<input name="create_password" type="password" class="input-xlarge" required>
                                <br>
								<label>Email</label>
                                <br>
								<input name="create_email" type="email" class="input-xlarge" required>
                                <br>
								<label>Phone Number</label>
                                <br>
								<input name="create_number" type="tel" class="input-xlarge" required>
                                <br>
			                    <br> 
								<div>
								  <input name="create" class="btn btn-primary" type="submit" value="Create">
								</div>
							  </form>
							</div>
						</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
    </section>
			
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>
    <script src="js/agency.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
</body>

</html>
