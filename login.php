<?php
	include('connectionData.txt');
   
	$mysqli = new mysqli($server, $user, $pass, $dbname, $port)
	or die('Error connecting');
?>

<html>
<head>
     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="icon" href="./assets/favicon.jpg">
     <title>PeelPages</title>
	 <link href="./css/bootstrap.min.css" rel="stylesheet">
	 <link rel="stylesheet" href="css/styles.css"> 
	 <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	 <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
	<div class="container" style="    margin-left: 37%;">
	<?php
		$username=$_POST['username'];
		$password=$_POST['password'];
		//validate the username and password
		if(isset($username) && isset($password) && !empty($username) && !empty($password))
		{
			$stmt = $mysqli -> prepare("SELECT * FROM auth WHERE username ='$username' AND password = '$password';");
			$stmt->execute();
			$userID=null;
			$stmt->bind_result($userID,$username1,$password1);
			$stmt->store_result();
			while($stmt->fetch())printf('',$userID,$username1,$password1);	
			//if user exists, redirect to index page
			if($stmt->num_rows==1)
			{
				session_start();
				$_SESSION['auth']='true';
				header('location:index.php');
			}
			else{
				echo "<h2>Wrong username or password...</h2>";
			}
		}
	?>
		<!--form for sending username and password-->
		<form style="margin-top:130px;" method="POST">
			<table>
				<tr>
					<td>
						<p>Username: </p>
					</td>
					<td>    
						<input type="text" name="username">
					</td>
				</tr>
				<tr>
					<td>
						<p>Password: </p>
					</td>
					<td>    
						<input type="password" name="password">
					</td>
				</tr>
				<tr>
					<td>
						<p></p>
					</td>
					<td>    
						<input class="btn btn-success" style="float:right;margin-top:5px;" type="submit">
					</td>
				</tr>
			</table>
		</form>
	</div>
 </body>
</html>