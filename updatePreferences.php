<?php 
 include('./connectionData.txt');
 $mysqli = new mysqli($server, $user, $pass, $dbname, $port)
 or die('Error connecting');
 ?>
<?php
//echo "arsoteinarst";
$name = $_POST['name'];
$thing = $_POST['thing'];
echo $name;
echo $thing;
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


	
