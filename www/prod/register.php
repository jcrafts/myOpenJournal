<?php require('includes/cookiecheck.inc'); 
	require('includes/dbconnect.inc');

  // Inialize session
session_start();

// Check, if user is already login, then jump to secured page
CookieIsSet('index.php');
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
	<!--<link rel="stylesheet" type="text/css" href="register.css"  media="screen">-->
    
    <!-- Menu style and javascript -->
    <!--<link type="text/css" href="../../css/menu.css" rel="stylesheet" /> 
    
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/menu.js"></script>-->
</head>

<body>

<div id="container">
	
	<div id="header"><img src="images/blackbanner.png" border="0"/></div>
	
	<div id="nav">
		<?php include("navigation.php"); ?>
	</div>

	<div id="body_content">
	<div id="content">
		<h2 align="center">Registration</h2>
		<p><br></p>

		<?php
	if($loggedin)
	{
		$username=$_SESSION['username'];
		$dbcnx= new MySQL;
		$dbcnx->MySQL();

		$query = "SELECT first_name, last_name
	     	            FROM user
			   WHERE username = '".mysql_real_escape_string($username)."'";
			      
		$result = mysql_query($query);
		$dbcnx->close();
		if (!$result)   
		{
		    echo("<P>Error performing query: " .
		    mysql_error() . "</P>");
		    exit();
		}
		$info=mysql_fetch_array($result);
		list($first_name, $last_name)=$info;
		    
		echo "Hello $first_name  $last_name, you are currently logged in as user ".$username."<BR>";
		echo "Please log out before registering a new account. <a href='logout.php'>logout</a>";
	    
	}
	else
	{

                
echo <<<HTML
       	 	<form  method="post" action="register2.php"><b>
 
		 <label for="first_name">First Name </label><br/>
		 <input type="text" name="first_name" id="textbox" class="textbox" autocomplete="off">&nbsp;
			<span class="expl"></span>
		 <br />

		 <label for="last_name">Last Name </label><br/>
		 <input type="text" name="last_name" id="textbox" class="textbox,alpha" autocomplete="off">&nbsp;
			<span class="expl"></span>
		 <br />


		 <label for="username">Desired Username </label><br/>
		 <input type="text" name="username" id="textbox" class="textbox,alphanum" autocomplete="off">&nbsp;
			<span class="expl"></span>
		 <br />
		 

		 <label for="password">Choose a password </label><br/>
		 <input type="password" name="password" id="textbox" class="textbox,alphanum" autocomplete="off">&nbsp;
			<span class="expl"></span>
		 <br />

		 <label for="re_password">Re-enter password </label><br/>
		 <input type="password" name="re_password" id="textbox" class="textbox,alphanum" autocomplete="off">&nbsp;
			<span class="expl"></span>
		 <br />

		 <label for="email">Email Address </label><br/>
		 <input type="text" name="email" id="textbox" class="textbox,email" autocomplete="off">&nbsp;
			<span class="expl"></span>
		 <br />
		 
		 <label for="affiliation">Affiliation </label><br/>
		 <input type="text" name="affiliation" id="textbox" class="textbox,alphanum" autocomplete="off">&nbsp;
			<span class="expl"></span>
		 <br />

		 <label for="credentials">Credentials </label>
		 <br> <textarea rows="10" cols = "70" name ="credentials" wrap="physical"></textarea><br>
		 
		 <br />
		 
		 <input type="submit" name="submit_button" id="submit" value="Register"></b>
		 
		</form>
HTML;
        
        
	}

	?>
	</div>
	</div>
	
	<?php include("footer.shtml"); ?>

</div>
	
</body>

</html>
