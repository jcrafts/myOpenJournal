<?php require('includes/cookiecheck.inc'); 


  // Inialize session
session_start();
?>
<html>

<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>My Open Journal</title>
    
    <meta name="description" content="My Open Journal" />
	<meta name="keywords" content="my open journal" />
    
    <meta name="author" content="CMPSC 483 W" />
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/universal.css" />
    <link rel="stylesheet" type="text/css" href="css/page.css" />
    
    <!-- Menu style and javascript -->
    <!--<link type="text/css" href="../../css/menu.css" rel="stylesheet" /> 
    
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/menu.js"></script>-->
</head>

<body>

<div id="container">
	
	<div id="header"><img src="images/blackbanner.png" border="0"/></div>
	
	<div id="nav">
		<?php 
			include("navigation.php");
		?>
	</div>

	<div id="body_content">
	<div id="content">
		<h2 align="center">Contact Us</h2>

		<?php
			$ebits = ini_get('error_reporting');
			error_reporting($ebits ^ E_NOTICE);
			// Change these two variables to meet your needs.

			//$myemail = 'WEBSITE_TECHEMAIL@WHEREEVER.COM';
			//$subject = 'Website Contact';

			$op = $_POST[op];

			if($op == 'contact')
			{
				$myemail = stripslashes($_POST[their_email]);
				$name = stripslashes($_POST[name]);
				$email = stripslashes($_POST[email]);
				$subject = stripslashes($_POST[subject]);
				$text = stripslashes($_POST[text]);
				$referer = $_POST[referer];
				$remote_host = $_SERVER[REMOTE_ADDR];
				$server = $_SERVER[SERVER_NAME];
				$browser = $_SERVER[HTTP_USER_AGENT];

				if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$",$email)) 
				{ 
					$status = "We're sorry, but you've entered an incorrect email address.<br>";
				}
				if(!$name)
				{
					$status .= "Please enter your name.<br>";
				}
				if(!$subject)
				{
					$status .= "Please enter a subject.<br>";
				}
				if(!$text)
				{
					$status .= "Please enter a message.<br>";
				}

				if(!$status)
				{
					$header = "From: $email \r\n";

					if($myemail=="WEBSITE_TECH_EMAIL@WHEREVER.COM")
					{
						$message = "
							Name: $name
							Email: $email
							Referer: $referer
							Site: $server
							Remote Host: $remote_host
							Remote Browser: $browser

							$text
						";
					}
						
					else
					{
						$message = "
			Name: $name
			Email: $email

			$text
						";
					}
					
					if(mail('mif10@psu.edu', $subject, $message, $header))
					{
						$status = "<br><br><h2>Thank you for your Feedback!!</h2><br><br>";
					}
					else
					{
						$status = "There was a problem sending your feedback, please try again later.<br><br>";
					}

				}
				else
				{
					$status .= "<br>Please press <u>back</u> on your browser to resubmit.<br><br>";
				}
			}    

			// Now check the referer page and ensure it's a proper URL

			$referer = $_SERVER[HTTP_REFERER];

			if(!preg_match('#^http\:\/\/[a-z0-9-]+.([a-z0-9-]+.)?[a-z]+#i', $referer))
			{
				unset($referer);
			}

		?>
		<?php print $status; ?>
			<ul>
			<form method="post" action="<?php print $_SELF; ?>">
			   <input type="hidden" name="op" value="contact">
			   <input type="hidden" name="referer" value="<?php print $referer; ?>">
			   Your Name:<br><input name="name" size="35" value=""><br>
			   Your E-mail address:<br><input name="email" size="35" value=""><br>
			   <br />Subject:<br /><input name="subject" size="35" value="" /><br />
			   <br>Message:<br><textarea name="text" cols="50" rows="10"></textarea><br><br>
			   <input type="submit" value="Send message!">
			</form></ul>
			<br />
	</div>
	<?php include("footer.shtml"); ?>
	</div>
	
	

</div>
	
</body>

</html>
