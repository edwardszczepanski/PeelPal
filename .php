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
			$fName=null;
			$lName=null;
			$stmt->bind_result($user_id,$username,$password,$fName,$lName);
			$stmt->store_result();
			while($stmt->fetch())printf('',$user_id,$username,$password,$fName,$lName);	
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
<div id="createGoalModal" class="modal" style="z-index: 3;">
				<!-- Modal content -->
				<div class="modal-content">
					<span id="" class="close closeCreateGoal">&times;</span>
					<h3>Please fill information</h3>
					<form action="contacts.php" method="POST" id="senddForm" style="margin-top: 2%;">	
						<table class="addContact">
							<tr>
								<td>
									<p>First Name:	</p>
								</td>
								<td>
									<input type="text" name="add_fName" id="add_fName">
								</td>
								<td>
									<p>Last Name:	</p>
								</td>
								<td>
									<input type="text" name="add_lName" id="add_lName">
								</td>
							</tr>
							<tr>
								<td>
									<p>Phone:	</p>
								</td>
								<td>
									<input type="text" name="add_phone" id="add_phone">
								</td>
								<td>
									<p>Email:	</p></td>
								<td>
									<input type="text" name="add_email" id="add_email">
								</td>
							</tr>
							<tr>
								<td>
									<p>Address1:	</p>
								</td>
								<td>
									<input type="text" name="add_address1" id="add_address1">
								</td>
								<td>
									<p>Address2:	</p>
								</td>
								<td>
									<input type="text" name="add_address2" id="add_address2">
								</td>
							</tr>
							<tr>	
								<td>
									<p>City: </p>
								</td>
								<td>
									<input type="text" name="add_city" id="add_city">
								</td>
								<td>
									<p>State: </p>
								</td>
								<td>
									<input type="text" name="add_state" id="add_state">
								</td>
							</tr>
							<tr>
								<td>
									<p>Zip:	</p>
								</td>
								<td>
									<input type="text" name="add_zip" id="add_zip">	
								</td>
								<td>
									<input style="display: none;"type="text" name="addId" value="<?php echo $addId;?>">								
								</td>
								<td>
									<input class="btn btn-success" type="submit" value="Save" style="float: right;">
									<input class="btn btn-success" type="reset" value="Erase" style="float: right; margin-right: 20px;">		
								</td>
							</tr>
						</table>
					</form> 
				</div>
			</div>
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
				<h1 style="color: white" class="section-heading">Welcome <?php echo $fName;?>!</h1>
				<div class="fb-share-button" data-href="http://ix.cs.uoregon.edu/~wang18/p2/PeelPal/goals.php" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="http://ix.cs.uoregon.edu/~wang18/p2/PeelPal/goals.php">??</a></div>
			</div>
		</div>
		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">
		<button id="createGoalBtn" class="btn btn-primary portfolio-link" >New Goal</button> 
	<a href="achievements.php" class="btn btn-primary">Achievements</a>
        <P></p>
		<div class="row">
								    
			<form method="POST" id="hForm">
			<input type="text" name="selectedGoal_id" id="selectedGoal_id" style="display:none;">
			<?php
			$stmt = $mysqli -> prepare("SELECT goal_id,g_name,goal_type,last_act,DAYOFYEAR(last_act), DAYOFYEAR(startDate), COUNT(*) AS numProg, TIMESTAMPDIFF (DAY,goal.startDate,goal.last_act) AS day_diff FROM goal join contribution c on(goal.goal_id=c.g_id) WHERE c.evaluate='positive' AND g_state=0 AND goal_type=0 GROUP BY goal_id;");
			$stmt->execute();
			$goal_id=null;
			$goal_name=null;
			$goal_type=null;		
			$last_act=null;		
			$last_day=null;		
			$first_day=null;
			$day_diff=null;	

			$num_progress=null;		
			$stmt->bind_result($goal_id, $goal_name, $goal_type, $last_act, $last_day, $first_day,$num_progress,$day_diff);
			$stmt->store_result();
			while($stmt->fetch())printf('
              <div class="col-md-4 col-sm-6 portfolio-item">
                 <!-- <a id="" href="#" class="portfolio-link" data-toggle="modal" onClick="sendHForm();">-->
                  <a id="aclick" href="#" class="portfolio-link" data-toggle="modal" >
                      <div class="portfolio-hover">
                          <div class="portfolio-hover-content">
                              <i class="glyphicon glyphicon-eye-open" style="font-size:80px;"></i>
                          </div>
                      </div>
                      <img src="img/%s.png" class="img-responsive portfolio-radius" alt="">
                  </a>
                  <div class="portfolio-caption">
							<input style="display:none;" value="%s" type="text" name="click_goal_id" id="%s">
                      <h4>%s</h4>
                      <p class="text-muted">Last Activity %s</p>
 					  <div class="progress">
						  <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:%s%%">
						  
						  </div>
					  </div>
                  </div>
              </div>
			  ',$goal_type,$goal_id, $goal_id, $goal_name,$last_act,$num_progress/($day_diff)*100);
			  ?>

			
			<?php
			$stmt = $mysqli -> prepare("SELECT g.goal_id,g.g_name,g.goal_type,g.last_act ,t.t_value, SUM(u.evaluate_num) AS tmpProg FROM goal g JOIN target t ON(g.goal_id=t.goal_id) JOIN updateprog u ON(g.goal_id=u.goal_id) WHERE g.g_state=0 GROUP BY u.goal_id;");
			$stmt->execute();
			$goal_id=null;
			$goal_name=null;
			$goal_type=null;		
			$last_act=null;		
			$targetVal=null;		
			$tmpTotal=null;		
			$stmt->bind_result($goal_id, $goal_name, $goal_type, $last_act, $targetVal, $tmpTotal);
			$stmt->store_result();
			while($stmt->fetch())printf('
              <div class="col-md-4 col-sm-6 portfolio-item">
                  <a id="bclick" href="#quackcon" class="portfolio-link" data-toggle="modal">
                      <div class="portfolio-hover">
                          <div class="portfolio-hover-content">
                              <i class="glyphicon glyphicon-eye-open" style="font-size:80px;"></i>
                          </div>
                      </div>
                      <img src="img/%s.png" class="img-responsive portfolio-radius" alt="">
                  </a>
                  <div class="portfolio-caption">
					<input style="display:none;" value="%s" type="text" name="click_goal_id" id="">
                      <h4>%s</h4>
                      <p class="text-muted">Last Activity %s</p>
 					  <div class="progress">
						  <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:%s%%">
						  
						  </div>
					  </div>
                  </div>
              </div>
			  ',$goal_type,$goal_id, $goal_name,$last_act,$tmpTotal/$targetVal*100);
			  ?>
			<form/>

			</div>
        </div>
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
