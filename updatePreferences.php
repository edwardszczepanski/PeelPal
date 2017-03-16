<?php 
 include('./connectionData.txt');
 $mysqli = new mysqli($server, $user, $pass, $dbname, $port)
 or die('Error connecting');
 ?>
<?php
$goalID = $_POST['identification'];
$selected = $_POST['selected'];
//echo "UPDATE goal SET notification = '$selected' WHERE goal_id = $goalID;";

$stmt = $mysqli -> prepare("UPDATE goal SET notification = '$selected' WHERE goal_id = '$goalID';");
$stmt -> execute(); 
?>


	
