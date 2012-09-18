<?php require('includes/cookiecheck.inc'); 
	require('includes/dbconnect.inc');

  // Inialize session
session_start();



?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/universal.css" />
    <link rel="stylesheet" type="text/css" href="css/profile.css" />
    <title>Secured Page</title>
</head>

<body>

<div id="container">
	<div id="header"><img src="images/blackbanner.png" border="0"/></div>
	
	<div id="nav">
		<?php 
		$email=$_SESSION["email"];
		if(isset($email))
		{
			include("navigation.php"); 
			include('includes/mail_functions.inc');
						
			closedb();

		}
		else
		{
			header('Location: error.php');
		}			
		?>
		
	</div>
		
	<div id="content">
		<center><h2>Confirm Registration </h2></center>
		<BR>
		<BR>
		<center><p><?php registerConfirmEmail($email); ?></p></center>
		<BR>
		<BR>
		<h3>Your Almost done! </h3> </b> 
		<br> <p>Please check your email to confirm and complete registration!</p>
		<BR> <p>To have another email sent . Click <a href="securedpage.php">resend</a></p><br>	
                	
	</div>               
	<?php include("footer.shtml"); ?>
</div>
</body>

</html>
