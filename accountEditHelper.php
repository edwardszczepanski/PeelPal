<?php 
 include('./connectionData.txt');
 $mysqli = new mysqli($server, $user, $pass, $dbname, $port)
 or die('Error connecting');
 ?>
<?php
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$phone_num = $_POST['phone_num'];
$userEditID = $_POST['userEditID'];

$stmt = $mysqli -> prepare("UPDATE user SET username = '$username', password = '$password', email = '$email', phone_num = '$phone_num' WHERE user_id = '$userEditID';");
$stmt -> execute(); 

?>


	
 <form id="sendAccountBKForm" action="accountsInfo.php" method="post">
<?php
    echo "<input type='hidden' name='username' value='$username'>";
    echo "<input type='hidden' name='userID' value='$userEditID'>";
    
?>
</form>
<script type="text/javascript">
    document.getElementById('sendAccountBKForm').submit();	
</script>
