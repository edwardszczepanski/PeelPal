<?php 
 include('oldScaffolding/connectionData.txt');
 $mysqli = new mysqli($server, $user, $pass, $dbname, $port)
 or die('Error connecting');
 ?>

<?php 
//echo '->'.$_POST['selectedGoal_id'].'<br>';
$selectedGoal_id=$_POST['selectedGoal_id'];

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
                </ul>
            </div>
        </div>
    </nav>

    <section id="portfolio" class="bg-light-gray">

		<div class="row">
			<div class="col-lg-12 text-center">
			<?php
			$stmt = $mysqli -> prepare("SELECT g_name FROM goal WHERE goal_id=$selectedGoal_id;");
			$stmt->execute();
			$top_goal_name=null;
					
			$stmt->bind_result($top_goal_name);
			$stmt->store_result();
			while($stmt->fetch())printf('<h1 style="margin-left: 20%%; color: white;text-align: left;" class="section-heading">%s</h1>',$top_goal_name);
			
			?>
			</div>
		</div>
		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">		
		<div class="row">			
								    
			<?php
			
			$stmt = $mysqli -> prepare("SELECT goal_id,g_name,goal_type,last_act,DAYOFYEAR(last_act), DAYOFYEAR(startDate), COUNT(*) AS numProg, TIMESTAMPDIFF (DAY,goal.startDate,goal.last_act) AS day_diff FROM goal join contribution c on(goal.goal_id=c.g_id) WHERE c.evaluate='positive' AND g_state=0 AND goal_type=0 AND goal.goal_id=$selectedGoal_id GROUP BY goal_id;");
			$stmt->execute();
			$goal_id=null;
			$goal_name=null;
			$goal_type=null;		
			$last_act=null;		
			$last_day=null;		
			$first_day=null;
			$day_diff=null;	

			$num_progress=null;		
			$stmt->bind_result($goal_id, $goal_name, $goal_type, $last_act, $last_day, $first_day,$num_progress, $day_diff);
			$stmt->store_result();
			while($stmt->fetch())printf('
              <div class="col-md-9 col-sm-8 portfolio-item">
                  
                  <div style="padding: 0px; margin: 0px; max-width: 760px;" class="portfolio-caption">
 					  <div style="height: 40px;    margin-top: 20px;" class="progress">
						  <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:%s%%">
						  
						  </div>
					  </div>
                  </div>
              </div>
			  ',$num_progress/($day_diff)*100);		  
			  ?>

			
		</div>
		
		<div class="row">
			<div class="col-md-9 col-sm-8 portfolio-item">
				<table class="table table-hover" style="    background-color: white;">
					<thead>
					  <tr>
						<th>Date</th>
						<th>Description</th>
						<th></th>
					  </tr>
					</thead>
					<tbody>
					<?php
					$stmt = $mysqli -> prepare("SELECT description, g_date FROM contribution WHERE g_id=$selectedGoal_id;");
					$stmt->execute();
					$tgdescription=null;
					$tgDate=null;		
					$stmt->bind_result($tgdescription, $tgDate);
					$stmt->store_result();
					while($stmt->fetch())printf('<tr><td>%s</td><td>%s</td><td><button id="edit_contact" type="button" class="btn btn-info" onclick="">Edit</button></td></tr>',$tgDate, $tgdescription);		  
					?>
					</tbody>
				</table>
			</div>
			
      
		
		</div>
		<a href="#codeday" class="btn btn-primary portfolio-link" data-toggle="modal">ADD CONTRIBUTION</a>
        <a href="#" class="btn btn-primary" onClick="complete_goal_button_cb()">MARK AS COMPLETE</a>
        <a href="#" class="btn btn-primary" onClick="abandon_goal_button_cb()">ABANDON GOAL</a>
        </div>
    </section>

<!--mark goal as complete modal-->
<div id="complete_modal" class="modal">
	<div class="modal-content">
		<h3>Do you really want to mark this goal as complete?</h3>
		<h3><font color= "red" >This will move the goal to your achievements page, and it will no longer be viewable in your list of active goals.</font></h3>
		<button type="button" id="complete_modal_yes" class="btn btn-primary">Yes</button>
		<button type="button" id="complete_modal_no" class="btn btn-primary">No</button>
		<input style="display: none;" type="text" name="completion_flag" id="completion_flag">
	</div>
</div>

<!--abandon goal modal-->
<div id="abandon_modal" class="modal">
	<div class="modal-content">
		<h3>Do you really want to abandon this goal?</h3>
		<h3><font color= "red" >This will be permanent, data will not be recoverable</font></h3>
		<button type="button" id="abandon_modal_yes" class="btn btn-primary">Yes</button>
		<button type="button" id="abandon_modal_no" class="btn btn-primary">No</button>
		<input style="display: none;" type="text" name="del_flag" id="del_flag">
	</div>
</div>

<script>
//complete goal modal functionality
function complete_goal_button_cb() {	
	var complete_modal = document.getElementById('complete_modal');

	complete_modal.style.display = "block";
	
	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == complete_modal) {
			complete_modal.style.display = "none";
		}
	}
	
	//defining cb for when user clicks no
	document.getElementById('complete_modal_no').onclick = function(event) {
		complete_modal.style.display = "none";
	}

	//defining cb for when user clicks yes 
	document.getElementById('complete_modal_yes').onclick = function(event) {
		complete_modal.style.display = "none";
		document.getElementById("completion_flag").value="1";
		//redirect to goals.php somehow
	}
}

//abandon goal modal functionality
function abandon_goal_button_cb() {	
	var abandon_modal = document.getElementById('abandon_modal');

	abandon_modal.style.display = "block";
		
	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == abandon_modal) {
			abandon_modal.style.display = "none";
		}
	}
	
	//defining cb for when user clicks no
	document.getElementById('abandon_modal_no').onclick = function(event) {
		abandon_modal.style.display = "none";
	}

	//defining cb for when user clicks yes 
	document.getElementById('abandon_modal_yes').onclick = function(event) {
		abandon_modal.style.display = "none";
		document.getElementById("del_flag").value="1";
		//redirect to goals.php somehow
	}
}
</script>


<?php
	//delete the goal if it has been marked for deletion
	$del_flag = $_POST['del_flag'];
	if($del_flag=="1"){
		$stmt = $mysqli -> prepare("DELETE FROM peelPal.goal WHERE goal_id='".$selectedGoal_id."'");
		$stmt->execute();
		$stmt = $mysqli -> prepare("DELETE FROM peelPal.contribution WHERE goal_id='".$selectedGoal_id."'");
		$stmt->execute();
	}
?>

<?php
	//complete the goal if it has been marked for completion
	$completion_flag = $_POST['completion_flag'];
	if($completion_flag=="1"){
		$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET g_state=1 WHERE goal_id='".$selectedGoal_id."'");
		$stmt->execute();
		
	}
?>

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
