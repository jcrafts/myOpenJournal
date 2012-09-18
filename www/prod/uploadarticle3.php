<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>My Open Journal | Upload your Article</title>
    
    <meta name="description" content="My Open Journal" />
    <meta name="keywords" content="my open journal" />
    
    <meta name="author" content="CMPSC 483 W" />
    
    <!-- CSS Stylesheets -->
    <!--<link rel="stylesheet" type="text/css" href="../../css/universal.css" />-->
    <link rel="stylesheet" type="text/css" href="../includes/upload.css" />
	    <link rel="stylesheet" type="text/css" href="css/universal.css" />
	<link rel="stylesheet" type="text/css" href="css/page.css" />
    
    <!-- Menu style and javascript -->
    <!--<link type="text/css" href="../../css/menu.css" rel="stylesheet" /> 
    
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/menu.js"></script>-->	
	
	
	<?php include("includes/cookiecheck.inc");
		require('includes/dbconnect.inc');
	session_start();
	include("includes/emailchecker.inc"); 
	include("includes/functions.php"); 
	//if(!ini_set("upload_tmp_dir", "/var/www/html/tmp"))
	//	die ("Couldn't make tmp dir\n");
	// Include database connection settings

	function createRandomPassword($pw_len) {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
    		while ($i <= $pw_len) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
	    	return $pass;
	}

	$dbcnx= new MySQL;
	$dbcnx->MySQL();
	?>

		<div id="container">
	
	<div id="header"><img src="images/blackbanner.png" border="0"/></div>
	
	<div id="nav">
		<?php include("navigation.php"); ?>
	</div>
	<div id="body_content">
	<div id="content">


	<?php
	$author_email = $_POST["author_email"];
	$author_first_name = $_POST["author_first_name"];
	$author_last_name = $_POST["author_last_name"];
	$author_username = $_POST["author_username"];
	$encounter_problem = false;
	$final_check = false;
	foreach(array_keys($author_email) as $i)
	{
		$first_name = mysql_real_escape_string($author_first_name[$i]);
		$last_name = mysql_real_escape_string($author_last_name[$i]);
		$email = mysql_real_escape_string($author_email[$i]);
		$doc_number = $_POST["doc_num"][$i];
		$new_author_check = true;
		if($first_name == Null  || strlen($first_name)<2)
		{ 
			echo " <font color=red>  *    First name is required and must contain 2 characters or more <br></font>";
			$new_author_check = false;
		}			     
		if($last_name == Null || strlen($last_name)<2 )
		{ 
			echo "<font color=red>  *    Last name is required and must contain 2 characters or more <br></font>";				  				$new_author_check = false;
		}
		if(check_email_address($email) == false )
		{
			echo " <font color=red>  *    Invalid Email Address <br></font>";
			$new_author_check = false;
		}
		if (isEmailAvail($email) == false)
		{
			if(isEmailConfirmed($email) == true)
			{
				echo " <font color=red>  *    Email email already exists <br></font>";
				$new_author_check = false;
			}
		}
		if(!$new_author_check)
		{
			if(!$encounter_problem)
			{
				echo "<form method='post' action='uploadarticle3.php'>";
				$encounter_problem = true;
			}
				echo "Email Address: <input name='author_email[]' type='input' /><br />";
				echo "First Name: <input name='author_first_name[]' type='input' value='".$first_name."' /><br />";
				echo "Last Name: <input name='author_last_name[]' type='input' value='".$last_name."' /><br /><br />";
				echo "<input name='doc_num[]' type='hidden' value='".$doc_number."' />";
				echo "<input name='author_username[]' type='hidden' value='".$author_username[$i]."' />";
				echo "<input type='submit' value = ' Save Author Data ' /></form>";
		}
		else
		{
			$user_password = createRandomPassword(10);
			$encrypt_user_password = md5($user_password);
			$insert_user = mysql_query("INSERT INTO users (username, password, join_date, email, first_name, last_name) VALUES ('$author_username[$i]', '$encrypt_user_password', CURDATE(), '$email', '$first_name', '$last_name')") or die(mysql_error());
			echo "<br />All good man last else";
			$u_id = mysql_insert_id();
			$add_author = "INSERT INTO authors (user_id, document_number) VALUES ('$u_id', '$doc_number')";
			$author_query = mysql_query($add_author) or die(mysql_error());
			$to = $email;
			$subject = "OpenJournal: Added as Author";
			$message = "Hello,\n\nAn article has been created listing you as an author.\n\nUsername: ".$author_username[$i]."\nPassword: ".$user_password."\n\nThanks,\n\nMyOpenJournal Team";
			$headers = 'From: bjw5121@psu.edu' . "\r\n" .
					    'Reply-To: bjw5121@psu.edu' . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();
			
			if(mail($to, $subject, $message, $headers))
			{
				$final_check = true;
			}
		}
	}
	if($encounter_problem)
	{
		echo "</form>";
	}
	if($final_check)
	{
		$doc_number = $_POST["doc_num"][0];
		$goto_doc = "viewarticle.php?id=" . $doc_number;
		header("Location: " . $goto_doc);
	}
	else
	{
		echo " <font color=red>  *    Could not send message to author <br></font>";
	}
	$dbcnx->close();
	?>	

</div></div><?php include("footer.shtml"); ?>
</div>
</div>

</html>
