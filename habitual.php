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

<?php
	/*If adding contribution: */
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
			$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET last_act='".$today."' WHERE goal_id='".$selectedGoal_id."';");
			$stmt ->execute();
			}
	}

	/*If editing a contribution: */
	$change_on_date = $_POST['date_of_original'];
	$new_description = $_POST['edit_description'];
	$new_type = $_POST['edit_c_type'];

	if(!empty($new_type)){
		$stmt = $mysqli -> prepare("SELECT con_id FROM peelPal.contribution WHERE g_id='".$selectedGoal_id."' and g_date='".$change_on_date."';");
		$stmt->execute();
		$con_id = null;
		$stmt -> bind_result($con_id);
		while($stmt->fetch())printf('',$con_id);
/*		$stmt = $mysqli -> prepare("UPDATE peelPal.contribution SET description='"$.new_description."', evaluate='".$new_type."' WHERE con_id='".$con_id."';");
		$stmt->execute();
*/		}
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
					while($stmt->fetch())printf('<tr><td>%s</td><td>%s</td><td><button id="editModalBtn" type="button" class="btn btn-info" onclick="pop_Edit()" value=%s >Edit</button></td></tr>',$tgDate, $tgdescription, $tgDate);		  
					?>
					</tbody>
				</table>
			</div>
			
      
		
		</div>
		<a id="addModalBtn" class="btn btn-primary portfolio-link" onclick="pop_Add()" >ADD CONTRIBUTION</a>
        <a class="btn btn-primary" onClick="complete_goal_button_cb()">MARK AS COMPLETE</a>
        <a class="btn btn-primary" onClick="abandon_goal_button_cb()">ABANDON GOAL</a>
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
							<td><input type="text" name="add_description" /></td>
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
					<label id="test_label" />
					<table>
						<tr>
							<td><label>Description</label></td>
							<td><input type="text" name="edit_description" id="edit_desc"/></td>
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
							<input type="text" name="date_of_original" id="edit_date"  />
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

/*	<script type="text/JavaScript"language="javascript">
	$(function(){
    
		$(editModalBtn).click(function() {
        		var $contrib_date = 'something'; document.getElementById(editModalBtn).value;
		        $(edit_cont_id).value = $contrib_date;
		        var $da = $(this).parent().children()[0];
        		var $de = $(this).parent().parent().children()[1];
        		var $da1 = $da.children;
        		var $da11 = $da1[0].innerHTML;
        		var $de1 = $de.children;
        		var $de11 = $de1[0].innerHTML;
        		$(edit_date).val($da11);
        		$(edit_desc).val($de11);
        		var $edit_eve = $(this).prev().prev().val();
        
    		});
    
	});
	</script>
*/	
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
    edit_btn.onclick = function(){
	var $edit_date = "something"; /*document.getElementById("edit_date");*/
        var $da = $(this).parent().children()[0];
        var $edit_date_val = /*document.getElementById(editModalBtn).value;*/
        $edit_date.value = $edit_date_val;
	var tst_lbl = document.getElementById("test_label");
	$(tst_lbl).val("this is a test");
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
    <script src="js/agency.js"></script>

    <script type="text/javascript" src="js/script.js"></script>

</body>


</html>
