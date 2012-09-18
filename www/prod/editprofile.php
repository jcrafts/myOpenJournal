<?php require('includes/cookiecheck.inc'); 
	require('includes/dbconnect.inc');

  // Inialize session
session_start();

// Check, if user is already login, then jump to secured page
//CookieIsSet('index.php');
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
		<h2 align="center">Edit Profile</h2>
		<p><br></p>

		<?php
	if(Cookie_IsNotSet())
	{
		echo " <font color=red> The following errors occured: <br></font>";
		echo " <font color=red>  *    You must be logged in <br></font>";
	}
	else
	{
		if(isset($_SESSION['registerFail']))
		{ 
		    echo " <font color=red> The following errors occured: <br></font>";
			unset($_SESSION['registerFail']);
		}

		if(isset($_SESSION['fnameTextFail']) )
		{ 
		    echo " <font color=red>  *    First name is required and must contain 2 characters or more <br></font>";
			unset($_SESSION['fnameTextFail']);
		}
	     
		if(isset($_SESSION['lnameTextFail']) )
		{ 
		    echo "<font color=red>  *    Last name is required and must contain 2 characters or more <br></font>";
			unset($_SESSION['lnameTextFail']);
		}
	   
		if (isset($_SESSION['unameNullFail']))
		{
			echo " <font color=red>  *    Username is required and must be alpha-numeric <br></font>";
			unset($_SESSION['unameNullFail']);
		}
		
		if (isset($_SESSION['passwordfail']))
		{
			echo " <font color=red>  *    The two passwords must match <br></font>";
			unset($_SESSION['passwordfail']);
		}

		if (isset($_SESSION['emailFail']))
		{
			echo " <font color=red>  *    Email must be valid <br></font>";
			unset($_SESSION['emailFail']);
		}
	     
		if(isset($_SESSION['unameTextFail']) )
		{ 
		    echo " <font color=red>  *    Username already exists! <br></font>";
			unset($_SESSION['unameTextFail']);
		}
		if(isset($_COOKIE['username']))
		{
			$uname = $_COOKIE['username'];
		}
		else
		{
			$uname = $_SESSION['username'];
		}

		include_once('includes/dbconnect.inc');
		$dbcnx = new MySQL();
		
		$query_user_id = mysql_query("SELECT * FROM users WHERE username = '$uname'") or die(mysql_error());
                $user_array = mysql_fetch_assoc($query_user_id);
		$first_name = $user_array["first_name"];
		$last_name = $user_array["last_name"];
		$email = $user_array["email"];
		$credentials = $user_array["credentials"];
		$affiliations = $user_array["affiliations"];

echo '       	 <form name="vform" id="vform" enctype="multipart/form-data" method="post" action="editprofileback.php"><b>';
echo '       	<label for="first_name">First Name </label><br/>';
echo '		<input type="text" name="first_name" id="textbox" class="textbox" autocomplete="off" value="'.$first_name.'">&nbsp';
echo '			<span class="expl"></span>';
echo '		 <br />';

echo '		 <label for="last_name">Last Name </label><br/>';
echo '		 <input type="text" name="last_name" id="textbox" class="textbox,alpha" autocomplete="off" value="'.$last_name.'">&nbsp;';
echo '			<span class="expl"></span>';
echo '		 <br />';


echo '		 <label for="password">Change password </label><br/>';
echo '		 <input type="password" name="password" id="textbox" class="textbox,alphanum" autocomplete="off">&nbsp;';
echo '			<span class="expl"></span>';
echo '		 <br />';

echo '		 <label for="re_password">Re-enter password </label><br/>';
echo '		 <input type="password" name="re_password" id="textbox" class="textbox,alphanum" autocomplete="off">&nbsp;';
echo '			<span class="expl"></span>';
echo '		 <br />';

echo '		 <label for="email">Email Address </label><br/>';
echo '		 <input type="text" name="email" id="textbox" class="textbox,email" autocomplete="off" value="'.$email.'">&nbsp;';
echo '			<span class="expl"></span>';
echo '		 <br />';
		 
echo '		 <label for="affiliation">Affiliation </label><br/>';
echo '		 <input type="text" name="affiliation" id="textbox" class="textbox,alphanum" autocomplete="off" value="'.$affiliations.'">&nbsp;';
echo '			<span class="expl"></span>';
echo '		 <br />';

echo '		 <label for="credentials">Credentials </label>';
echo '		 <br> <textarea rows="10" cols = "70" name ="credentials" wrap="physical" value="'.$credentials.'"></textarea><br>';
		 
echo '		 <br />';

echo '		 <label for="affiliation">Picture </label><br/>';
echo '		 <input type="file" name="picture" id="file" class="textbox,alphanum">&nbsp;';
echo '			<span class="expl"></span>';
echo '		 <br />';

echo '		<br />';
		 
echo '		 <input type="submit" name="submit_button" id="submit" value="Edit Profile"></b>';
		 
echo '		</form>';
        
	}
	$dbcnx->close();
	?>
	</div>
	</div>
	
	<?php include("footer.shtml"); ?>

</div>
	
</body>

</html>
