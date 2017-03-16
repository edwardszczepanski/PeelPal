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
 or die('Error connecting');
 ?>
<?php
		//timer: pageload start
		//$time_start = microtime(true);

		$username=$_SESSION['username'];
		$password=$_SESSION['password'];
		
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
		}			
	?>

<?php	
	$goalTypePicked = $_POST['goalTypePicked'];			
	$NoticeTypePicked = $_POST['NoticeTypePicked'];			
	$goalDesccription = $_POST['goalDesccription'];			
	$gdInput = $_POST['gdInput'];			
	$tnInput = $_POST['tnInput'];			
	$sdInput = $_POST['sdInput'];			

	//chech the goal which is going to be created
	if((!empty($goalTypePicked))||(!empty($goalDesccription))){			
		//make sure whether it has been already created
		$stmt = $mysqli -> prepare("SELECT COUNT(*) FROM peelPal.goal g WHERE (g.g_name ='".$goalDesccription."') AND (g.goal_type ='".$goalTypePicked."') AND (g.notification = '".$NoticeTypePicked."');");
		$stmt->execute();
		$countNum=null;
		$stmt->bind_result($countNum);		
		while($stmt->fetch())printf('',$countNum);			
		//if goal is ok to be created
		if($countNum<1)
		{			
			//check the goal type to insert into correct table
			if($goalTypePicked==0){
				$stmt = $mysqli -> prepare("INSERT INTO peelPal.goal (g_name,u_id, goal_type, notification,startDate) VALUES ('".$goalDesccription."','".$user_id."','".$goalTypePicked."','".$NoticeTypePicked."',CURDATE());");
				$stmt->execute();
			}else{
				$stmt = $mysqli -> prepare("INSERT INTO peelPal.goal (g_name,u_id, goal_type, notification,startDate,endDate) VALUES ('".$goalDesccription."','".$user_id."','".$goalTypePicked."','".$NoticeTypePicked."',CURDATE(),'".$gdInput."');");
				$stmt->execute();
				$stmt = $mysqli -> prepare("SELECT g.goal_id FROM peelPal.goal g WHERE (g.g_name ='".$goalDesccription."') AND (g.goal_type ='".$goalTypePicked."') AND (g.notification = '".$NoticeTypePicked."') AND (g.endDate = '".$gdInput."');");
				$stmt->execute();
				$newGoalId=null;
				$stmt->bind_result($newGoalId);		
				while($stmt->fetch())printf('',$newGoalId);			
				$stmt = $mysqli -> prepare("INSERT INTO peelPal.target (t_value,s_value, goal_id,l_value) VALUES ('".$tnInput."','".$sdInput."','".$newGoalId."','".$sdInput."');");
				$stmt->execute();

			}
		}
	}	
?>  	

<!DOCTYPE html>
<html lang="en">
<head style="background-color: #ffcc10">
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
<!--modal for input goal information when creating-->
<div id="createGoalModal" class="modal" style="">
				<!-- Modal content -->
				<div class="modal-content">
					<span id="" class="close closeCreateGoal">&times;</span>
					<h3>Create New Goal</h3>
					<form action="goals.php" method="POST" id="senddForm" style="margin-top: 2%;">	
						<table class="createGoal">
							<tr>
								<td>
									<!-- New goal types would require adding option here -->
									<p>Is your new goal habitual or quantitative?</p>
								</td>
							</tr>
							<tr>
								<td>
									<div id="createTable">
										<label class="buttonColor" id="habitualLable"><input type="radio" name="goalTypePicked" value="0" ><span>habitual</span></label><label class="buttonColor" id="quantitativeLabel"><input type="radio" name="goalTypePicked" value="1" ><span>quantitative</span></label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<p>How do you want to be notified for your goal?</p>
								</td>
							</tr>
							<tr>
								<td>
									<div id="createTable">
										<label class="buttonColor"><input type="radio" name="NoticeTypePicked" value="Email"><span>Email</span></label><label class="buttonColor"><input type="radio" name="NoticeTypePicked" value="Text"><span>Text</span></label><label class="buttonColor"><input type="radio" name="NoticeTypePicked" value="None"><span>None</span></label>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<p>Please give a brief description of your goal</p>
								</td>
							</tr>
							<tr>
								<td>
									<textarea rows="5" cols="50" name="goalDesccription" id="goalDesccription" required></textarea>
									
								</td>
							</tr>
							<tr>
								<td>
									<div class="row" id="sdInputDiv" style="margin-top:10px;visibility: hidden;">
										<p class="col-md-8">What is your start number?</p>		
										<input class="col-md-4" style="" id="sdInput" name="sdInput"></input>
									</div>
									
								</td>
							</tr>
							
							<tr>
								<td>
									<div class="row" id="tnInputDiv" style="visibility: hidden;">
										<p class="col-md-8">What is your target number?</p>
										<input class="col-md-4" style="" id="tnInput" name="tnInput"></input>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row" id="gdInputDiv" style="visibility: hidden;">
										<p class="col-md-8">What is your goal deadline?</p>		
										<input class="col-md-4" style="" id="gdInput" type="date" name="gdInput"></input>
									</div>
									
								</td>
							</tr>
							<tr>
 								<td>
 									<input class="btn btn-success" type="submit" value="Save" style="float: right;">
 								</td>
 							</tr>
						</table>
					</form> 
				</div>
			</div>
<div id="fb-root"></div>
<script>
//share on facebook function  
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_CN/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

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

    <section id="portfolio" class="bg-light-gray">

		<div class="row">
			<div class="col-lg-12 text-center">
				<h1 style="color: white" class="section-heading">Welcome <?php echo $username;?>!</h1>
			</div>
		</div>
		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">
	
	<button id = "createGoalBtn" class = "btn btn-primary portfolio-link" >New Goal</button>
		 
	<button id="achievementBtn" class="btn btn-primary">Achievements</button>
	


	<form id="achievementForm" method = "GET" id="sendForm">
		<input style = "display: block" type = "hidden" name = "userID" value = "<?php echo $user_id; ?>" >
		<input style = "display: block" type = "hidden" name = "username" value = "<?php echo $username; ?>" >
	</form>
	

        <P></p>
		<div class="row">
								    
			<form method="POST" id="hForm">
			<input type="text" name="selectedGoal_id" id="selectedGoal_id" style="display:none;">
			<?php
			//list all existing habitual goals for user
			$stmt = $mysqli -> prepare("SELECT g.goal_id, g.g_name, g.goal_type, (CASE g.last_act WHEN '0000-00-00' THEN 'N/A' ELSE g.last_act END) AS last_act,DAYOFYEAR(g.last_act), DAYOFYEAR(g.startDate),nihao.numProg, TIMESTAMPDIFF (DAY,g.startDate,CURDATE()) AS day_diff FROM goal g LEFT JOIN (SELECT goal_id,g_name,goal_type,last_act,DAYOFYEAR(last_act), DAYOFYEAR(startDate), COUNT(*) AS numProg, TIMESTAMPDIFF (DAY,goal.startDate,CURDATE()) AS day_diff FROM goal left outer join contribution c on(goal.goal_id=c.g_id) WHERE c.evaluate='positive' AND g_state=0 AND goal_type=0 GROUP BY goal_id) AS nihao ON g.goal_id = nihao.goal_id WHERE g.g_state=0 AND g.u_id=$user_id AND g.goal_type=0;");			
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
			  ',$goal_type,$goal_id, $goal_id, $goal_name,$last_act,$num_progress/($day_diff + 1)*100);
			  ?>

			
			<?php
			//list all existing quantitative goals for user
			$stmt = $mysqli -> prepare("SELECT g.goal_id,g.g_name,g.goal_type,(CASE g.last_act WHEN '0000-00-00' THEN 'N/A' ELSE g.last_act END) AS last_act, ABS(t.l_value - t.s_value)/ABS(t.t_value - t.s_value) diff FROM goal g JOIN target t ON g.goal_id = t.goal_id WHERE g.g_state=0 AND g.u_id=$user_id;");
			$stmt->execute();
			$goal_id=null;
			$goal_name=null;
			$goal_type=null;		
			$last_act=null;					
			$tar_diff=null;		
			$stmt->bind_result($goal_id, $goal_name, $goal_type, $last_act, $tar_diff);
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
			  ',$goal_type,$goal_id, $goal_name,$last_act,$tar_diff*100);
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
       
<script type="text/JavaScript"language="javascript">
	//set up click funtion for accessing habitual goal
	$(aclick).click(function() {
		var $selGoal_id=$(this).siblings().children();		
		var $correctChild=$selGoal_id[0].value;
		$(selectedGoal_id).val($correctChild);
		document.getElementById("hForm").action="././habitual.php";
		document.getElementById("hForm").submit();
	});
	
</script>
<!-- New goal types would require addition here -->
<script type="text/JavaScript"language="javascript">	
	//get the quantitative attributes
	$(quantitativeLabel).click(function(){
		if (($(gdInputDiv).css('visibility') === 'hidden')&&($(tnInputDiv).css('visibility') === 'hidden')) {
			$(gdInputDiv).css('visibility', 'visible');
			$(tnInputDiv).css('visibility', 'visible');
			$(sdInputDiv).css('visibility', 'visible');
		}
	});

	//get the habitual attributes
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
	//set up click funtion for accessing quantitative goal
	$(bclick).click(function() {
		var $selGoal_id=$(this).siblings().children();		
		var $correctChild=$selGoal_id[0].value;
		$(selectedGoal_id).val($correctChild);
		document.getElementById("hForm").action="././quantitative.php";
		document.getElementById("hForm").submit();
	});
 
</script>

<script type="text/JavaScript"language="javascript">
	//set up click funtion for accessing achievement page
	$(achievementBtn).click(function() {
		document.getElementById("achievementForm").action="././achievements.php";
		document.getElementById("achievementForm").submit();
	});
	//set up click funtion for accessing account information
	$(accountBtn).click(function() {
		document.getElementById("accountForm").action="././accountsInfo.php";
		document.getElementById("accountForm").submit();
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

	<?php
		//pageload timer stop
		//$time_end = microtime(true);
		//$runtime = ($time_end - $time_start)*1000;
		//echo "load time is ";
		//echo $runtime;
		//echo "ms";
	?>
</html>
