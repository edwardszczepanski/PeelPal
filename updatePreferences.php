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

/*
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$phone_num = $_POST['phone_num'];
$userEditID = $_POST['userEditID'];

$stmt = $mysqli -> prepare("UPDATE user SET username = '$username', password = '$password', email = '$email', phone_num = '$phone_num' WHERE user_id = '$userEditID';");
$stmt -> execute(); 
 */

?>


	
