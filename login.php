<?php 
 include('oldScaffolding/connectionData.txt');
 $mysqli = new mysqli($server, $user, $pass, $dbname, $port)
 or die('Error connecting');
 ?>
<?php
		//$username=$_POST['username'];
		//$password=$_POST['password'];
		$username="tomz";
		$password="123456";
		//echo "username: ".$username."<br>";
		//validate the username and password
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
			/*
			if($stmt->num_rows==1)
			{
				session_start();
				$_SESSION['auth']='true';
				header('location:index.php');
			}
			else{
				echo "<h2>Wrong username or password...</h2>";
			}*/
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
				<!--<div class="fb-share-button" data-href="http://ix.cs.uoregon.edu/~wang18/p2/PeelPal/goals.php" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="http://ix.cs.uoregon.edu/~wang18/p2/PeelPal/goals.php">??</a></div>-->
			</div>
		</div>
		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">
		<button id="createGoalBtn" class="btn btn-primary portfolio-link" >New Goal</button> 
	<a href="achievements.php" class="btn btn-primary">Achievements</a>
        <P></p>
    </section>
			
			
<script type="text/JavaScript"language="javascript">
	//create contact modal
    // Get the modal
    var modal = document.getElementById('createGoalModal');
    // Get the button that opens the modal
    var btn = document.getElementById("createGoalBtn");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("closeCreateGoal")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }
    
    span.onclick = function() {
        modal.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
	</script>
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
       
<script type="text/JavaScript"language="javascript">
	$(aclick).click(function() {
		var $selGoal_id=$(this).siblings().children();		
		var $correctChild=$selGoal_id[0].value;
		$(selectedGoal_id).val($correctChild);
		document.getElementById("hForm").target='_blank';  
		document.getElementById("hForm").action="././habitual.php";
		document.getElementById("hForm").submit();
	});
	
	
	$(quantitativeLabel).click(function(){
		if (($(gdInputDiv).css('visibility') === 'hidden')&&($(tnInputDiv).css('visibility') === 'hidden')) {
			$(gdInputDiv).css('visibility', 'visible');
			$(tnInputDiv).css('visibility', 'visible');
			$(sdInputDiv).css('visibility', 'visible');
		}
	});


	$(habitualLable).click(function(){
		if (($(gdInputDiv).css('visibility') === 'visible')&&($(tnInputDiv).css('visibility') === 'visible')) {
			$(gdInputDiv).css('visibility', 'hidden');
			$(tnInputDiv).css('visibility', 'hidden');
			$(sdInputDiv).css('visibility', 'hidden');
			$(gdInput).val('');
			$(tnInput).val('');
			$(sdInput).val('');
		}
	});	
 
</script>
<script type="text/JavaScript"language="javascript">
	$(bclick).click(function() {
		var $selGoal_id=$(this).siblings().children();		
		var $correctChild=$selGoal_id[0].value;
		$(selectedGoal_id).val($correctChild);
		document.getElementById("hForm").target='_blank';  
		document.getElementById("hForm").action="././quantitative.php";
		document.getElementById("hForm").submit();
	});
 
</script>
    <script src="js/jquery.js"></script>

    <script src="js/bootstrap.min.js"></script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>


    <script src="js/agency.js"></script>

    <script type="text/javascript" src="js/script.js"></script>
</body>

</html>