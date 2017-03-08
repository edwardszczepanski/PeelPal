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
include('oldScaffolding/connectionData.txt');
$mysqli = new mysqli($server, $user, $pass, $dbname, $port)
or die ("Connection failed");
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
                        <a class="page-scroll" href="#portfolio">Dashboard</a>
                    </li>
                    <li>
                    	<a class="page-scroll" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
				<h1 style = "color: white;" class = "section-heading"> <?php echo $username ?>: ACHIEVEMENTS </h1>
			<!--
				<h1 style = "color: white; text-align: center;" class = "section-heading"> ACHIEVEMENTS </h1>
				-->
			</div>
		</div>

		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">				
		<div class="row">
			<div>
				<table class="table table-hover" style="    background-color: white;">
					<thead>
					  <tr>
						<th>Goal</th>
						<th>End Date</th>
						<th>Days to Complete</th>
						<th>Trophies</th>
						<th></th>
					  </tr>
					</thead>
					<tbody>
					<?php
						# Query database for user-specific achievements (using user_id, not username)
						$stmt = $mysqli -> prepare("SELECT g_name, endDate, DATEDIFF(endDate, startDate), trophy FROM goal WHERE g_state = 1 AND u_id='$userID';");
						$stmt -> execute(); 
						$goalName = null;
						$endDate = null;
						$daysToComplete = null;
						$trophies = null;	
						$stmt -> bind_result($goalName, $endDate, $daysToComplete, $trophies);	
						$stmt -> store_result();
						while($stmt->fetch())printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $goalName, $endDate, $daysToComplete, $trophies);	
					#echo "SELECT g_name, endDate, DATEDIFF(endDate, startDate), trophy FROM goal WHERE g_state = 1 AND u_id='$userID';";
					?>

					</tbody>
				</table>
			</div>
		</div>
      </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <span class="copyright">Copyright &copy; PeelPal 2017</span>
                    <a href="#page-top" title="To Top" class="page-scroll" style="padding:10px">
                    </a>
                </div>
            </div>
        </div>
    </footer>
       
    <script src="js/jquery.js"></script>

    <script src="js/bootstrap.min.js"></script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>


    <script src="js/agency.js"></script>

    <script type="text/javascript" src="js/script.js"></script>
</body>

</html>
