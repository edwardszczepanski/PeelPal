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

 include('./connectionData.txt');
 $mysqli = new mysqli($server, $user, $pass, $dbname, $port)
 or die('Error connecting');
 ?>

<?php 
//get the goal_id from the goal.php page
$selectedGoal_id=$_POST['selectedGoal_id'];

?>



<!DOCTYPE html>
<html lang="en">
<head style="background-color: #ffcc10">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=320, height=device-height">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.js"></script>

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

<!--add update-->
<?php		
	//start update timer
	//$start_run = microtime(true);
	//get the info. that the user filled
	$add_date = $_POST['add_date'];			
	$add_des = $_POST['add_des'];			
	$add_num = $_POST['add_num'];	
	$selectedGoal_id=$_POST['selectedGoal_id'];
	
	if((!empty($add_date))||(!empty($add_des))){	
		$stmt = $mysqli -> prepare("SELECT COUNT(*) FROM peelPal.updateprog u WHERE (u.description ='".$add_des."') AND (u.evaluate_num ='".$add_num."') AND (u.date ='".$add_date."') AND (u.goal_id ='".$selectedGoal_id."');");
		$stmt->execute();
		$countNum=null;
		$stmt->bind_result($countNum);		
		while($stmt->fetch())printf('',$countNum);			
		
		if($countNum<1)
		{	
			//insert update info into database		
			$stmt = $mysqli -> prepare("INSERT INTO `peelPal`.`updateprog` (`description`, `evaluate_num`, `date`, `goal_id`) VALUES ('".$add_des."','".$add_num."','".$add_date."','".$selectedGoal_id."');");
			$stmt->execute();
			//update the last update date in database
			$stmt = $mysqli -> prepare("UPDATE `peelPal`.`goal` SET `last_act`='".$add_date."' WHERE `goal_id`='".$selectedGoal_id."';");
			$stmt->execute();
			//update the latest number in database
			$stmt = $mysqli -> prepare("UPDATE `peelPal`.`target` SET `l_value`='".$add_num."' WHERE `goal_id`='".$selectedGoal_id."';");
			$stmt->execute();
			//Get target value for trophy calculation
			$stmt = $mysqli -> prepare("SELECT t_value FROM peelPal.target WHERE goal_id='".$selectedGoal_id."';");
			$stmt->execute();
			$target = null;
			$stmt->bind_result($target);
			while($stmt->fetch())printf('',$target);
			//Get current trophy count
			$stmt = $mysqli -> prepare("SELECT trophy FROM peelPal.goal WHERE goal_id='".$selectedGoal_id."';");
			$stmt->execute();
			$t_count = null;
			$stmt->bind_result($t_count);
			while($stmt->fetch())printf('',$t_count);
			//Calculate current percent and percent for next trophy(no trophies given above 75%)
			if($t_count < 3){
				$c_percent = $add_num/$target;
				echo $c_percent;
				$t_percent = ($t_count+1) * .25;
			//if current percent meets next trophy level, increment trophy count in database
				if($c_percent >= $t_percent){
					echo "adding trophy";
					$t_count = $t_count + 1;
					echo "adding trophy count ";
					echo $t_count;
					$stmt = $mysqli -> prepare("UPDATE peelPal.goal SET trophy='".$t_count."' WHERE goal_id='".$selectedGoal_id."';");
					$stmt->execute();
				}
			}
		}
		//end update timer
		/*$end_run = microtime(true);
		$running_time = ($end_run - $start_run)*1000;
		echo "adding update took ";
		echo $running_time;
		echo "ms \n";*/
	}	
?> 

<!--edit update-->
<?php		
	$edit_date = $_POST['edit_date'];			
	$edit_des = $_POST['edit_des'];			
	$edit_num = $_POST['edit_number'];	
	$selectedGoal_id=$_POST['selectedGoal_id'];
	$upgr_id= $_POST['contact_Id'];
		
	if((!empty($edit_date))||(!empty($edit_des))){			
		$stmt = $mysqli -> prepare("SELECT COUNT(*) FROM peelPal.updateprog u WHERE u.up_id ='".$upgr_id."';");
		$stmt->execute();
		$countNum=null;
		$stmt->bind_result($countNum);				
		while($stmt->fetch())printf('',$countNum);	
				
		if($countNum==1)
		{	
			//update the update_info. in database
			$stmt = $mysqli -> prepare("UPDATE `peelPal`.`updateprog` SET `description`='".$edit_des."', `evaluate_num`='".$edit_num."', `date`='".$edit_date."' WHERE `up_id`='".$upgr_id."'; ");
			$stmt->execute();
			
			//get the current goal's last update's date
			$stmt = $mysqli -> prepare("SELECT last_act FROM peelPal.goal WHERE goal_id = '".$selectedGoal_id."'; ");
			$stmt->execute();
			$edit_ls=null;
			$stmt->bind_result($edit_ls);				
			while($stmt->fetch())printf('',$edit_ls);			
			//echo"<script>alert(wusuowei);</script>";		
			
			//check the update update's date is the last update's date or not
			if($edit_date == $edit_ls)
			{
				//update the last update's date for the current goal
				$stmt = $mysqli -> prepare("UPDATE `peelPal`.`target` SET `l_value`='".$edit_num."' WHERE `goal_id`='".$selectedGoal_id."'; ");
				$stmt->execute();
			}	


		}
	}	
?>

<!--load the targetnumber and latest number-->
<?php
	$selectedGoal_id=$_POST['selectedGoal_id'];
	$stmt = $mysqli -> prepare("SELECT t_value, s_value, l_value FROM target WHERE goal_id=$selectedGoal_id;");
	$stmt->execute();
	$t_value=null;
	$s_value=null;
	$l_value=null;

	$stmt->bind_result($t_value, $s_value, $l_value);
	$stmt->store_result();
	while($stmt->fetch())printf('',$t_value, $s_value, $l_value);
?>
	<!--add update modal-->
	<div id="myModal" class="modal">
		<!-- Modal content -->
		<div class="modal-content">
		<span id="add_close" class="close">&times;</span>
		<h3>Add Update</h3><p>Your current number is: <?php echo $l_value;?></p><p>Your target number is: <?php echo $t_value;?></p>
		<form action="quantitative.php" method="POST" id="senddForm" style="margin-top: 2%;">	
			<table class="addUpdate">
				<tr>
					<td>
						<p>Date:	</p>
					</td>
					<td>
						<input type="date" name="add_date" id="add_date" required>
					</td>
				</tr>
				<tr>
					<td>
						<p>Description:	</p>
					</td>
					<td>
						<input type="text" name="add_des" id="add_des" required>
					</td>
				</tr>
				<tr>
					<td>
						<p>Number:	</p>
					</td>
					<td>
						<input type="text" name="add_num" id="add_num" required>
					</td>
				</tr>
				<tr>
					<td>
						<input style="display: none;" type="text" name="selectedGoal_id" value="<?php echo $selectedGoal_id;?>">								
					</td>
					<td>
						<input class="btn btn-success" type="button" value="Save" style="float: right;" onclick="checkDate()">											
					</td>
				</tr>
			</table>
		</form> 
		</div>
	</div>

	<!--edit update modal-->		
	<div id="edit_myModal" class="modal">
        <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Edit Update</h3><p>Your current number is: <?php echo $l_value;?></p><p>Your target number is: <?php echo $t_value;?></p>
        <form action="quantitative.php" method="POST" id="sendForm" style="margin-top: 2%;">	
        	<table class="editUpdate">
				<tr>
					<td>
						<p>Date:	</p>
					</td>
					<td>
						<input type="text" name="edit_date" id="edit_da" readOnly="true" required>
					</td>
				</tr>                
				<tr>
					<td>
						<p>Description:	</p>
					</td>
					<td>
						<input type="text" name="edit_des" id="edit_de" required>
					</td>
				</tr>                
				<tr>		
					<td>
						<p>Number:	</p>
					</td>					
					<td>
						<input type="text" name="edit_number" id="edit_num" required>
					</td>								
				</tr>                				
				<tr>               
								
					<td>
						<input style="width: 50px; display: none;" type="text" name="selectedGoal_id" value="<?php echo $selectedGoal_id;?>">
						<input style="width: 50px; display: none;" type="text" name="contact_Id" id="edit_contact_Id">		
					</td>					
					<td>
						<input class="btn btn-success" type="submit" value="Save" style="float: right;">
								
					</td>
				</tr>				
            </table>
        </form>                  
        </div>
	</div>

<!--Account information-->
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

    	<!--Load the goal's name-->
		<div class="row">
			<div class="col-lg-12 text-center">
			<?php
			$selectedGoal_id=$_POST['selectedGoal_id'];
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
			
			if($trophies == 0)
			{
			echo "N/A";
			//echo "<i class='fa fa-trophy'></i>";
			}
			for ($x = 0; $x <= $trophies - 1; $x++) {
  				echo "<i class='fa fa-trophy'></i>";
  				} 
			?>
			</h3>
			<h4>GOAL STATE: <?php echo $l_value ?>/<?php echo $t_value ?></h4>
			</div>
		</div>
		<hr style="border-top: 2px solid #fff;">        
        <div class="container" style=" ">
        <div style="width: 100%;">
    <canvas id="myChart" style="width:600px; height:200px;"     ></canvas>
 </div>



<?php
$stmt = $mysqli -> prepare("SELECT u.evaluate_num, u.date FROM updateprog u WHERE goal_id = $selectedGoal_id ORDER BY u.date ;");
$stmt->execute();
$g_num=null;
$g_date=null;
$a=array();
$b=array();
$stmt->bind_result($g_num,$g_date);
$stmt->store_result();
while($stmt->fetch()){
	array_push($a,$g_num);
	array_push($b,$g_date);

}
?>
<script type="text/javascript">
 
var labels=['<?php echo implode("','",$b);?>'];
var data=['<?php echo implode("','",$a);?>'];
 

 
 var data = {
    labels,
    datasets: [
        {	
        	
            data,
            
        }]
};


    // Get the context of the canvas element we want to select
        var ctx = document.getElementById("myChart");
        var myBarChart = new Chart(ctx, {
                                            type: 'line',
                                            data: data,
                                            options: {
        scales: {
            label: [{
                display: false
            }]
        }
    }
                                    });
</script>		
		<!--Load the progress bar-->
		<div class="row">								    
    

			<?php
			$selectedGoal_id=$_POST['selectedGoal_id'];
			$stmt = $mysqli -> prepare("SELECT ABS(t.l_value - t.s_value)/ABS(t.t_value - t.s_value) diff FROM goal g JOIN target t ON g.goal_id = t.goal_id WHERE g.goal_id = $selectedGoal_id");
			$stmt->execute();
			$tar_diff=null;		
			$stmt->bind_result( $tar_diff);
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
			  ',$tar_diff*100);			  
			  ?>
		</div>
		<!--Load the goal's update information-->
		<div class="row">
			<div>
				<table id="getDate" class="table table-hover" style="    background-color: white;">
					<thead>
					  <tr>
						<th>Date</th>
						<th>Description</th>
						<th></th>
					  </tr>
					</thead>
					<tbody>
					<?php
					$selectedGoal_id=$_POST['selectedGoal_id'];
					$stmt = $mysqli -> prepare("SELECT description, date, evaluate_num, up_id FROM updateprog WHERE goal_id=$selectedGoal_id;");
					$stmt->execute();
					$tgdescription=null;
					$tgDate=null;
					$tgNum=null;
					$upId=null;		
					$stmt->bind_result($tgdescription, $tgDate, $tgNum, $upId);
					$stmt->store_result();
					while($stmt->fetch())printf('
					<tr>
					<td><p>%s</p></td>
					<td><p>%s</p></td>
					<td><input style="display: none;" type="text" name="%s" style="width:50px;" value="%s" >
						<input style="display: none;" type="text" name="%s" style="width:50px;" value="%s" >
					<button id="edit_contact" type="button" class="btn btn-info" onclick="pop_Edit()">Edit</button></td>
					
					</tr>',$tgDate, $tgdescription,$tgNum,$tgNum,$upId,$upId);		  
					?>
					</tbody>
				</table>
			</div>
		</div>
		<!--add update, mark as complete, abandon goal buttons-->
		<button id="myBtn" type="button" class="btn btn-primary portfolio-link" onclick="pop_Add()" >ADD UPDATE</button>
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
                $stmt = $mysqli -> prepare("DELETE FROM peelPal.target WHERE goal_id='".$selectedGoal_id."'");
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
		<form action="././quantitative.php" method="POST" id="completeForm" style="margin-top: 2%;">
        	        <button type="button" id="complete_modal_yes" class="btn btn-primary">Yes</button>
        	        <button type="button" id="complete_modal_no" class="btn btn-primary">No</button>
        	        <input style="display: none;" type="text" name="completion_flag" id="completion_flag">
			<input style="display: none;" type="text" name="selectedGoal_id" value="<?php echo $selectedGoal_id;?>">
		</form>
        </div>
</div>

<!--abandon goal modal-->
<div id="abandon_modal" class="modal">
        <div class="modal-content">
                <h3>Do you really want to abandon this goal?</h3>
                <h3><font color= "red" >This will be permanent, data will not be recoverable</font></h3>
		<form action="././quantitative.php" method="POST" id="deleteForm" style="margin-top: 2%;">
        	        <button type="button" id="abandon_modal_yes" class="btn btn-primary">Yes</button>
        	        <button type="button" id="abandon_modal_no" class="btn btn-primary">No</button>
			<input style="display: none;" type="text" name="del_flag" id="del_flag">
			<input style="display: none;" type="text" name="selectedGoal_id" value="<?php echo $selectedGoal_id;?>">
		</form>
        </div>
</div>


<script type="text/JavaScript"language="javascript">
	//validate the information input in add new update modal by user
	function checkDate() {
     	// Get the typed information
		var getb=document.getElementById("add_date").value;
		var geta=document.getElementById("add_des").value;
		var getd=document.getElementById("add_num").value;

		// Check user type a description or not
		if(geta == ''){
			alert("Please type a description!");
		}
		// Check user type a number as number or not
		if((isNaN(getd)) || getd == ''){
			alert("Please type a number!");
		}
		// Check user select a date already exits or not
		if(getb.length == 10 && geta !='' && getd !=''){
			var getc = '<p>' + getb + '</p>';
			var tb=document.getElementById("getDate");
			var flag = 0;
			for(var k=0;k<tb.rows.length;k++){
			var td=document.getElementById("getDate").rows[k].cells[0];
			if(getc == td.innerHTML){
			flag = 1;
			}


			}
			if(flag == 1){
			alert("You already update for this day!!");
			// Click no would not save the information
			}
			if(flag == 0){
			document.getElementById("senddForm").submit();
			}
		}

    	
	}
</script>
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

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>
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
	<script type="text/JavaScript"language="javascript">
	$(function(){
    // When the user clicks the button, open the modal
    // When the user clicks on <span> (x), close the modal
    //var modal = document.getElementById('myModal');  
    $(edit_contact).click(function() {
        var $contact_id_val = $(this).prev().val();
        //alert($contact_id_val);
        $(edit_contact_Id).val($contact_id_val);
        var $da = $(this).parent().parent().children()[0];
        var $de = $(this).parent().parent().children()[1];
        var $da1 = $da.children;
        var $da11 = $da1[0].innerHTML;
        var $de1 = $de.children;
        var $de11 = $de1[0].innerHTML;
        $(edit_da).val($da11);
        $(edit_de).val($de11);
        var $edit_eve = $(this).prev().prev().val();
        $(edit_num).val($edit_eve);

        
    });
    
	});
	</script>
	
	<script type="text/JavaScript"language="javascript">
	function pop_Add() {
    //create contact modal
    // Get the modal
    var modal = document.getElementById('myModal');
    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];
    // When the user clicks the button, open the modal
    modal.style.display = "block";
    
    // When the user clicks on <span> (x), close the modal
	span.onclick = function() {
        var modal = document.getElementById('myModal');
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal ) {
            modal.style.display = "none";
        }
    }
	}
	</script>


	
	<script type="text/JavaScript"language="javascript">
	function pop_Edit() {
    // Get the modal
    var edit_modal = document.getElementById('edit_myModal');
    // Get the button that opens the modal
    var edit_btn = document.getElementById("edit_contact");
    // Get the <span> element that closes the modal
    var edit_span = document.getElementsByClassName("close")[1];
    // When the user clicks the button, open the modal
    edit_modal.style.display = "block";
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
	/*$end_time = microtime(true);
	$load_time = ($end_time - $start_time)*1000;
	echo "loading page took ";
	echo $load_time;
	echo "ms";*/
?>

</html>
