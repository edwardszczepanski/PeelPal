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
	//start page load timer
	//$start_time = microtime(true);
?>


<?php 
 include('./connectionData.txt');
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

<body style="background-color: #ffcc10;" id="page-top" class="index">

<?php
	/*If adding contribution: */
	//timer start
	//$start_run = microtime(true);
	$today = date("Y-m-d");
	$description = $_POST['add_description'];
	$type = $_POST['add_c_type'];

	if(!empty($type)){


		if (!($stmt = $mysqli -> prepare("SELECT COUNT(*) FROM peelPal.contribution WHERE g_date='".$today."' and g_id='".$selectedGoal_id."';"))){
			echo " prepare failed";}
		if (!$stmt->execute()){
			echo "execute failed" . $stmt->error;}
		$countNum = null;
		if (!$stmt->bind_result($countNum)){
			echo "bind failed" . $stmt->error;}

		while($stmt->fetch())printf('',$countNum);            
		if($countNum<1){
			$stmt = $mysqli -> prepare("INSERT INTO `peelPal`.`contribution` (`description`, `evaluate`, `g_date`, `g_id`) VALUES ('".$description."','".$type."','".$today."','".$selectedGoal_id."');");
			$stmt->execute();
			$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET last_act='".$today."', update_mark='1' WHERE goal_id='".$selectedGoal_id."';");
			$stmt ->execute();
			if($type == 'positive'){
				/*If type is positive, increment progress value by 1*/
				$stmt = $mysqli -> prepare("SELECT progress FROM peelPal.goal WHERE goal_id='".$selectedGoal_id."';");
				$stmt->execute();
				$progress = null;
				$stmt->bind_result($progress);
				while($stmt->fetch())printf('',$progress);
				$progress = $progress + 1;
				$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET progress='".$progress."' WHERE goal_id='".$selectedGoal_id."';");
				$stmt->execute();
				//also check if trophies should be updated
				//if it's been 5 days since last trophy added:
				echo "checking trophy interval";
				$stmt = $mysqli -> prepare("SELECT DATEDIFF('".$today."', IFNULL(last_trophy,DATE_SUB((SELECT startDate FROM goal WHERE goal_id='".$selectedGoal_id."'), INTERVAL 1 DAY))) FROM peelPal.goal WHERE goal_id = '".$selectedGoal_id."';");
				$stmt->execute();
				$days = null;
				$stmt->bind_result($days);
				while($stmt->fetch())printf('', $days);
				echo $days;
				echo " checked trophy interval";
				if($days >= 5){
					echo "days too  long";
					$stmt = $mysqli -> prepare("SELECT evaluate FROM peelPal.contribution WHERE DATEDIFF('".$today."', IFNULL((SELECT last_trophy FROM peelPal.goal WHERE goal_id = '".$selectedGoal_id."'),DATE_SUB((SELECT startDate FROM goal WHERE goal_id='".$selectedGoal_id."'), INTERVAL 1 DAY))) <= 5;");
					$stmt->execute();
					$types = null;
					$stmt->bind_result($types);
					$deservesTrophy = True;
					while($stmt->fetch()){
						if($types != 'positive'){
							$deservesTrophy = False;
						}
					}
					if($deservesTrophy){
						//Get current trophy count, increment by 1 and update
						$stmt = $mysqli -> prepare("SELECT trophy FROM peelPal.goal WHERE goal_id='".$selectedGoal_id."';");
						$stmt -> execute();
						$t_count = null;
						$stmt -> bind_result($t_count);
						while($stmt->fetch())printf('',$t_count);
						$t_count = $t_count + 1;
						$stmt = $mysqli -> prepare("UPDATE goal SET trophy='".$t_count."', last_trophy='".$today."' WHERE goal_id='".$selectedGoal_id."';");
						$stmt -> execute();
					}
				}
			}
		}
		//end adding contribution running time
		//$end_run = microtime(true);
		//$running_time = ($end_run - $start_run)*1000;
		//echo "adding took ";
		//echo $running_time;
		//echo "ms";
	}

	/*If editing a contribution: */
	$edit_cont_id = $_POST['contrib_Id'];
	$new_description = $_POST['edit_description'];
	$new_type = $_POST['edit_c_type'];

	if(!empty($new_type)){
		//Get Old Type
		$stmt = $mysqli -> prepare("SELECT evaluate FROM peelPal.contribution WHERE con_id='".$edit_cont_id."';");
		$stmt->execute();
		$old_type = null;
		$stmt->bind_result($old_type);
		while($stmt->fetch())printf('',$old_type);
		$stmt = $mysqli -> prepare("UPDATE peelPal.contribution SET description='".$new_description."', evaluate='".$new_type."' WHERE con_id='".$edit_cont_id."';");
		$stmt->execute();
		//Compare old type to new type
		if($old_type != $new_type){
			//if positive changed to negative, decrement progress
			if($new_type == 'negative'){
				$stmt = $mysqli -> prepare("SELECT progress FROM peelPal.goal WHERE goal_id='".$selectedGoal_id."';");
				$stmt->execute();
				$progress = null;
				$stmt->bind_result($progress);
				while($stmt->fetch())printf('',$progress);
				$progress = $progress - 1;
				$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET progress='".$progress."' WHERE goal_id='".$selectedGoal_id."';");
				$stmt->execute();
				}			
			//if negative changed to positive, increment progress
			if($new_type == 'positive'){
				$stmt = $mysqli -> prepare("SELECT progress FROM peelPal.goal WHERE goal_id='".$selectedGoal_id."';");
				$stmt->execute();
				$progress = null;
				$stmt->bind_result($progress);
				while($stmt->fetch())printf('',$progress);
				$progress = $progress + 1;
				$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET progress='".$progress."' WHERE goal_id='".$selectedGoal_id."';");
				$stmt->execute();
				}
			}
		}
?>

	<?php
	$stmt = $mysqli -> prepare("SELECT u_id FROM goal WHERE goal_id='$selectedGoal_id';");
	$stmt -> execute(); 
	$acc_u_id = null;
	$stmt -> bind_result($acc_u_id);	
	$stmt -> store_result();	
	while($stmt->fetch())printf('', $acc_u_id);	
	?>
	<?php
	$stmt = $mysqli -> prepare("SELECT username FROM user WHERE user_id='$acc_u_id';");
	$stmt -> execute(); 
	$acc_username = null;
	$stmt -> bind_result($acc_username);	
	$stmt -> store_result();	
	while($stmt->fetch())printf('', $acc_username);	
	?>
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
		<input style = "display: block" type = "hidden" name = "userID" value = "<?php echo $acc_u_id; ?>" >
		<input style = "display: block" type = "hidden" name = "username" value = "<?php echo $acc_username; ?>" >
	</form>

    <section id="portfolio" class="bg-light-gray">

		<div class="row">
			<div class="col-lg-12 text-center">
			<?php
			$stmt = $mysqli -> prepare("SELECT g.goal_id, g.g_name, g.goal_type, g.last_act, DAYOFYEAR(g.last_act), DAYOFYEAR(g.startDate), nihao.numProg, TIMESTAMPDIFF (DAY,g.startDate,CURDATE()) AS day_diff FROM goal g LEFT JOIN (SELECT goal_id,g_name,goal_type,last_act,DAYOFYEAR(last_act), DAYOFYEAR(startDate), COUNT(*) AS numProg, TIMESTAMPDIFF (DAY,goal.startDate,CURDATE()) AS day_diff FROM goal left join contribution c on(goal.goal_id=c.g_id) WHERE c.evaluate='positive' AND g_state=0 AND goal_type=0 GROUP BY goal_id) AS nihao ON g.goal_id = nihao.goal_id WHERE g.goal_id = $selectedGoal_id; ");
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
			while($stmt->fetch())printf('',$day_diff, $num_progress);
			
			if($num_progress == ''){
			$num_progress = 0;
			}
			?>
			<?php
			$stmt = $mysqli -> prepare("SELECT g_name, trophy FROM goal WHERE goal_id=$selectedGoal_id;");
			$stmt->execute();
			$top_goal_name=null;
			$trophies = null;
					
			$stmt->bind_result($top_goal_name, $trophies);
			$stmt->store_result();
			while($stmt->fetch())printf('',$top_goal_name, $trophies);
			
			?>

			<h1 style="color: white;" class="section-heading"><?php echo $top_goal_name ?></h1><h3>Trophies: 
			<?php
			if($trophies <= 4 && $trophies > 0){
			for ($x = 0; $x <= $trophies - 1; $x++) {
  				echo "<i class='fa fa-trophy'></i>";
  				} 
			}
			else if($trophies == 0){
				echo "N/A";
			}
			else{
				echo $trophies;
				echo " ";
				echo x;
				echo " ";
				echo "<i class='fa fa-trophy'></i>";
			}
			?>
			<h4>GOAL STATE: <?php echo $num_progress ?>/<?php echo $day_diff + 1 ?></h4>
			</div>
		</div>
		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">		
		<div class="row">			
								    
			<?php
			
			$stmt = $mysqli -> prepare("SELECT g.goal_id, g.g_name, g.goal_type, g.last_act, DAYOFYEAR(g.last_act), DAYOFYEAR(g.startDate), nihao.numProg, TIMESTAMPDIFF (DAY,g.startDate,CURDATE()) AS day_diff FROM goal g LEFT JOIN (SELECT goal_id,g_name,goal_type,last_act,DAYOFYEAR(last_act), DAYOFYEAR(startDate), COUNT(*) AS numProg, TIMESTAMPDIFF (DAY,goal.startDate,CURDATE()) AS day_diff FROM goal join contribution c on(goal.goal_id=c.g_id) WHERE c.evaluate='positive' AND g_state=0 AND goal_type=0 GROUP BY goal_id) AS nihao ON g.goal_id = nihao.goal_id WHERE g.goal_id = $selectedGoal_id; ");
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
              <div>
                  
                  <div style="padding: 0px; margin: 0px;" class="portfolio-caption">
 					  <div style="height: 40px;    margin-top: 20px;" class="progress">
						  <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:%s%%">
						  
						  </div>
					  </div>
                  </div>
              </div>
			  ',$num_progress/($day_diff + 1)*100);		  
			  ?>
			
		</div>

		<div class="row">
			<div>
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
					$stmt = $mysqli -> prepare("SELECT description, g_date, evaluate, con_id FROM contribution WHERE g_id=$selectedGoal_id;");
					$stmt->execute();
					$tgdescription=null;
					$tgDate=null;		
					$tgEval=null;
					$stmt->bind_result($tgdescription, $tgDate, $tgEval, $con_id);
					$stmt->store_result();
					while($stmt->fetch())printf('<tr><td>%s</td><td>%s</td><td><input style="display: none;" type="text" name="%s" value="%s" /><input style="display: none;" type="text" name="%s" value="%s" />
						<input style="display: none;" type="text" name="%s" value="%s" /><button id="editModalBtn" type="button" class="btn btn-info" onclick="pop_Edit()"  >Edit</button>
						</td></tr>',$tgDate, $tgdescription, $tgdescription,$tgdescription,$tgEval,$tgEval, $con_id, $con_id);		  
					?>
					</tbody>
				</table>
			</div>
			
      
		
		</div>
		<a id="addModalBtn" class="btn btn-primary portfolio-link" onclick="pop_Add()" >ADD CONTRIBUTION</a>
        <a class="btn btn-primary" onClick="complete_goal_button_cb()">MARK AS COMPLETE</a>
        <a class="btn btn-primary" onClick="abandon_goal_button_cb()">ABANDON GOAL</a>

<div id="createTable" style="padding-top: 16px;">
<label class="newButtonColor"><input type="radio" name="NoticeTypePicked" value="Email"><span>Email</span></label>
<label class="newButtonColor"><input type="radio" name="NoticeTypePicked" value="Text"><span>Text</span></label>
<label class=newButtonColor"><input type="radio" name="NoticeTypePicked" value="None"><span>None</span></label>
<?php
    echo '<label class=newButtonColor"><input type="submit" value="' . $selectedGoal_id . '" id="datSubmitButton"><span>Submit</span></label>';
?>
</div>

        </div>
    </section>

<?php
	//delete the goal if it has been marked for deletion
	$del_flag = $_POST['del_flag'];
	if($del_flag==1){
		$stmt = $mysqli -> prepare("DELETE FROM peelPal.contribution WHERE g_id='".$selectedGoal_id."';");
		$stmt->execute();
		$stmt = $mysqli -> prepare("DELETE FROM peelPal.goal WHERE goal_id='".$selectedGoal_id."';");
		$stmt->execute();
		echo '<script>window.location.replace("goals.php");</script>';
	}
?>

<?php
	//complete the goal if it has been marked for completion
	$completion_flag = $_POST['completion_flag'];
	if($completion_flag==1){
		$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET g_state=1 WHERE goal_id='".$selectedGoal_id."';");
		$stmt->execute();
		$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET endDate=CURDATE() WHERE goal_id='".$selectedGoal_id."';");
		$stmt->execute();
		echo '<script>window.location.replace("goals.php");</script>';
	}
?>

<!--mark goal as complete modal-->
<div id="complete_modal" class="modal">
	<div class="modal-content">
		<h3>Do you really want to mark this goal as complete?</h3>
		<h3><font color= "red" >This will move the goal to your achievements page, and it will no longer be viewable in your list of active goals.</font></h3>
		<form action="././habitual.php" method="POST" id="completeForm" style="margin-top: 2%;">
			<button type="button" id="complete_modal_yes" class="btn btn-primary">Yes</button>
			<button type="button" id="complete_modal_no" class="btn btn-primary">No</button>
			<input style="display: none;" type="text" name="completion_flag" id="completion_flag" value="0">
			<input style="display: none;" type="text" name="selectedGoal_id" value="<?php echo $selectedGoal_id;?>">
		</form>
	</div>
</div>
<!--abandon goal modal-->
<div id="abandon_modal" class="modal">
	<div class="modal-content">
		<h3>Do you really want to abandon this goal?</h3>
		<h3><font color= "red" >This will be permanent, data will not be recoverable</font></h3>
		<form action="././habitual.php" method="POST" id="deleteForm" style="margin-top: 2%;">
			<button type="button" id="abandon_modal_yes" class="btn btn-primary">Yes</button>
			<button type="button" id="abandon_modal_no" class="btn btn-primary">No</button>
			<input style="display: none;" type="text" name="del_flag" id="del_flag" value="0">
			<input style="display: none;" type="text" name="selectedGoal_id" value="<?php echo $selectedGoal_id;?>">
		</form>
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
		document.getElementById("completeForm").submit();
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
		document.getElementById("deleteForm").submit();
	}
}
</script>


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

	<script>
    value = null;
    $("input:radio[name=NoticeTypePicked]").click(function() {
            value = $(this).val();
    });
    var goalID = $('#datSubmitButton').val()
	$('#datSubmitButton').click(function()
	{
		//alert("what the world is going on");
        if(value == null){
		  alert("Please Select an option");
            return;
        }
		 $.post( "updatePreferences.php", { identification: goalID, selected: value }, function(data) {
		  alert("Preferences Updated");
		})
		  .fail(function() {
			alert( "Error Updating Preferences" );
		  });
	});
	</script>


	<!-- Add Contribution Modal -->
	<div id="AddContModal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Add Contribution</h3>
			</div>
			<div class="modal-body"  autofocus="true">
			<form action="././habitual.php" method="POST" id="addform" style="margin-top: 2%;">
				<table>
					<tr>
						<td><label>Description</label></td>
						<td><input type="text" name="add_description" required="required" /></td>
					</tr>
					<tr>
						<td><label>Contribution Type</label></td>
						<td><select name="add_c_type" >
							<option value="positive">Positive</option>
							<option value="negative">Negative</option>
						</select></td>
					</tr>
					<tr>
						<input type="text" name="selectedGoal_id" value="<?php echo $selectedGoal_id;?>" style="display:none;" />
					</tr>
				</table>
				<button type="submit" class="btn btn-default" data-dismiss="modal">Submit Contribution</button>
			</form>
			</div>
		</div>
	</div>


	<!-- Edit Contribution Modal -->
	<div id="EditContModal" class="modal" modal.style.display="block">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Edit Contribution</h3>
			</div>
			<div class="modal-body"  autofocus="true">
			<form action="././habitual.php" method="POST" id="addform" style="margin-top: 2%;">
				<table>
					<tr>
						<td><label>Description</label></td>
						<td><input type="text" name="edit_description" id="edit_desc" required="required" /></td>
					</tr>
					<tr>
						<td><label>Contribution Type</label></td>
						<td><select id="edit_type" name="edit_c_type" >
							<option value="positive">Positive</option>
							<option value="negative">Negative</option>
						</select></td>
					</tr>
					<tr>
						<input type="text" name="selectedGoal_id" value="<?php echo $selectedGoal_id;?>" style="display:none;" />
						<input " type="text" name="contrib_Id" id="edit_contrib_Id" style="display:none;" />
					</tr>
				</table>
				<button type="submit" class="btn btn-default" data-dismiss="modal">Submit Contribution</button>				
			</form>
			</div>
		</div>
	</div>
 <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>
	
	<script type="text/JavaScript"language="javascript">
	function pop_Add() {
    //create add contribution modal
    // Get the modal
    var modal = document.getElementById('AddContModal');
    // Get the button that opens the modal
    var btn = document.getElementById("addModalBtn");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks the button, open the modal
    modal.style.display = "block";
    
    // When the user clicks on <span> (x), close the modal
    //var modal = document.getElementById('myModal');
	span.onclick = function() {
        var modal = document.getElementById('AddContModal');
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal ) {
            modal.style.display = "none";
            //edit_modal.style.display = "none";
        }
    }

	}
	</script>

	<script type="text/JavaScript"language="javascript">
	$(function(){
    
		$(editModalBtn).click(function() {
			var $cont_id_val = $(this).prev().val();
        		//alert($cont_id_val);
        		$(edit_contrib_Id).val($cont_id_val);

        		var $edit_dess = $(this).prev().prev().prev().val();
        		$(edit_desc).val($edit_dess);
			
        		var $e_eval = $(this).prev().prev().val();
			var $eval_select = document.getElementById('edit_type');
			for(var i, j = 0; i = $eval_select.options[j]; j++){
				if(i.value == $e_eval){
					$eval_select.selectedIndex = j;
					break;
				}
			}
    		});
    
	});
	</script>
	
	<script type="text/JavaScript"language="javascript">
	function pop_Edit() {
    // Get the modal
    var edit_modal = document.getElementById('EditContModal');
    // Get the button that opens the modal
    var edit_btn = document.getElementById("editModalBtn");
    // Get the <span> element that closes the modal
    var edit_span = document.getElementsByClassName("close")[1];
    // When the user clicks the button, open the modal
    //edit_btn.onclick = function() {
    edit_modal.style.display = "block";
    //}
    // When the user clicks on <span> (x), close the modal
    edit_span.onclick = function() {
        edit_modal.style.display = "none";
    }
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == edit_modal) {
            edit_modal.style.display = "none";
            event.cancelBubble = true;
        }
    }
	}
	</script>
	<script type="text/JavaScript"language="javascript">
	$(accountBtn).click(function() {
		document.getElementById("accountForm").action="././accountsInfo.php";
		document.getElementById("accountForm").submit();
	}); 
	</script>
    <script src="js/agency.js"></script>

    <script type="text/javascript" src="js/script.js"></script>

</body>

<?php
	//end page load timer
	//$end_time = microtime(true);
	//$load_time = ($end_time - $start_time)*1000;
	//echo "loading page took ";
	//echo $load_time;
	//echo "ms";
?>

</html>
