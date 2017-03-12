<?php
include('oldScaffolding/connectionData.txt');
$mysqli = new mysqli($server, $user, $pass, $dbname, $port)
or die ("Connection failed");
?>

<?php
# Checks if username and userID match in URL. If not, URL was likely manually changed to access
# a private achievements page. Redirects to logout.
$stmt = $mysqli -> prepare ("SELECT user_id FROM user WHERE username ='$_GET[username]' AND user_id ='$_GET[userID]';");
$stmt -> execute();
$res = null;
$stmt -> bind_result($res); 
$stmt->fetch();

if (! $res) { 
	echo "stop snoopin'";
	echo '<script type = "text/javascript">
		window.location="logout.php"
		</script>';
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head style="background-color: #ffcc10">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height">

    <title>Peel Pal</title>

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
<<<<<<< HEAD
<?php
# Getting posted variables from goals.php
$userID=$_POST['userID'];
# echo $_POST['userID'];
$username = $_POST['username'];
?>
=======

	<!-- Facebook Share Button: -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
  		var js, fjs = d.getElementsByTagName(s)[0];
  		if (d.getElementById(id)) return;
  		js = d.createElement(s); js.id = id;
  		js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8";
 		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

>>>>>>> bde10d16240328f5a639791bf8a10f43ddc6a55d
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
		<input style = "display: block" type = "hidden" name = "userID" value = "<?php echo $userID; ?>" >
		<input style = "display: block" type = "hidden" name = "username" value = "<?php echo $username; ?>" >

	</form>

<<<<<<< HEAD
=======
<?php
# Getting posted variables from goals.php
#$userID=$_POST['userID'];
# echo $_POST['userID'];
#$username = $_POST['username'];
?>
>>>>>>> bde10d16240328f5a639791bf8a10f43ddc6a55d

    <section id="portfolio" class="bg-light-gray">

		<!-- Displaying Achievements header with username -->
		<div class="row">
			<div class="col-lg-12 text-center">
				<!--
				<h1 style = "color: white;" class = "section-heading"> <?php #echo $username ?>: ACHIEVEMENTS </h1>
				-->				
				<h1 style = "color: white;" class = "section-heading"> <?php echo $_GET["username"] ?>: ACHIEVEMENTS </h1>
			</div>
		</div>

		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">				
		<div class="row">
			<div>
				<!-- Facebook Share Button: -->
				<center>
				<div class="fb-share-button" style="margin-bottom:2.6%" data-href="" data-layout="button" data-size="large" data-mobile-iframe="true">
					<a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fix.cs.uoregon.edu%2F%7Eadeodhar%2FPeelPal%2Fgoals.php&amp;src=sdkpreparse">Share</a>
				</div>
				</center>
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
						# Gathering data from database
						$stmt = $mysqli -> prepare("SELECT g_name, endDate, DATEDIFF(endDate, startDate), trophy FROM goal, user WHERE g_state = 1 AND u_id='$_GET[userID]' AND username='$_GET[username]';");
						$stmt -> execute(); 
						$goalName = null;
						$endDate = null;
						$daysToComplete = null;
						$trophies = null;	
						$stmt -> bind_result($goalName, $endDate, $daysToComplete, $trophies);	
						$stmt -> store_result();
						while($stmt->fetch())printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>', $goalName, $endDate, $daysToComplete, $trophies);		
					?>

					</tbody>
				</table>
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
	<script type="text/JavaScript"language="javascript">
	$(accountBtn).click(function() {
		document.getElementById("accountForm").action="././accountsInfo.php";
		document.getElementById("accountForm").submit();
	});
 
</script>
</body>

</html>
