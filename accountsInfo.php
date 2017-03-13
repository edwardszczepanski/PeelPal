 <?php
//start session 
session_start();
//check if variable is true, otherwise deny access
if(!$_SESSION['auth'])
{
    header('location:login.php');
}
?>
<?php
include('./connectionData.txt');
$mysqli = new mysqli($server, $user, $pass, $dbname, $port)
or die ("Connection failed");
?>

<!DOCTYPE html>
<html lang="en">
<head style="background-color: #ffcc10">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height">


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
                        <a class="page-scroll" href="././goals.php">Home</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)"  class="page-scroll" id="accountBtn" >Account</a>
                    </li>
                    <li>
                    	<a class="page-scroll" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
	<form id="accountForm" method = "POST">
		<input style = "display: block" type = "hidden" name = "userID" value = "<?php echo $user_id; ?>" >
		<input style = "display: block" type = "hidden" name = "username" value = "<?php echo $username; ?>" >
	</form>

<?php
# Getting posted variables from goals.php
$userID=$_POST['userID'];
# echo $_POST['userID'];
$username = $_POST['username'];

?>

    <section id="portfolio" class="bg-light-gray">

		<!-- Displaying Achievements header with username -->
		<div class="row">
			<div class="col-lg-12 text-center">
				<h1 style = "color: white;" class = "section-heading"> <?php echo $username ?>: ACCOUNT INFOMATION</h1>
			<!--
				<h1 style = "color: white; text-align: center;" class = "section-heading"> ACHIEVEMENTS </h1>
				-->
			</div>
		</div>

		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">				
		<div class="row">
			<div>
				<h4>Please edit your account information here</h4 >
				<form id="accountEditForm" method = "POST">
					<table class="table table-hover" style="background-color: white;">
						
						<?php
							# Query database for user-specific achievements (using user_id, not username)
							$stmt = $mysqli -> prepare("SELECT username, password, email, phone_num FROM user WHERE user_id='$userID';");
							$stmt -> execute(); 
							$username = null;
							$password = null;
							$email = null;
							$phone_num = null;	
							$stmt -> bind_result($username, $password, $email, $phone_num);	
							$stmt -> store_result();
							while($stmt->fetch())printf('<tr><td>Username: </td><td><input id ="username" name="username" value="%s"></td></tr><tr><td>Password: </td><td><input type="password" id ="password" name="password" value="%s"></td></tr><tr><td>Email: </td><td><input id ="email" name="email" value="%s"></td></tr><tr><td>Phone: </td><td><input id ="phone_num" name="phone_num" value="%s"></td></tr>', $username, $password, $email, $phone_num);	
						#echo "SELECT g_name, endDate, DATEDIFF(endDate, startDate), trophy FROM goal WHERE g_state = 1 AND u_id='$userID';";
						?>
					</table>
					<input style="display:none;" id ="userEditID" name="userEditID" value="<?php echo $userID; ?>">					

				</form>
				
				<button id="editSubmitBtn" class="btn btn-primary">Submit</button>
				<button id="" class="btn btn-primary" onclick="window.location.replace('goals.php');">Close</button>
							
			</div>
		</div>
      </div>
    </section>

    <!--footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <span class="copyright">Copyright &copy; PeelPal 2017</span>
                    <a href="#page-top" title="To Top" class="page-scroll" style="padding:10px">
                    </a>
                </div>
            </div>
        </div>
    </footer-->
       
    <script src="js/jquery.js"></script>

    <script src="js/bootstrap.min.js"></script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>


    <script src="js/agency.js"></script>

    <script type="text/javascript" src="js/script.js"></script>
	<script type="text/JavaScript"language="javascript">
	var flag;
	function checkEmpty() {
		var geta=document.getElementById("username").value;
		var getb=document.getElementById("password").value;
		//var getc=document.getElementById("email").value;
		//var getd=document.getElementById("phone_num").value;
		flag=1;
		if(geta == ''){
			alert("Username can't be empty!");
			flag=0;
		}
		if(getb == ''){
			alert("Password can't be empty!");
			flag=0;
		}
		/*
		if(getc == ''){
			alert("Please type a description!");
		}
		if(getd == ''){
			alert("Please type a description!");
		} */  	
	}
		
	$(editSubmitBtn).click(function() {
		checkEmpty();
		if(flag){
			document.getElementById("accountEditForm").action="././accountEditHelper.php";
			document.getElementById("accountEditForm").submit();
		}
	});

	
</script>
</body>

</html>
