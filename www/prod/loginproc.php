<?php
		$loginfailure=false;
		if (Cookie_IsNotSet() && isset($_GET['loginrequired']))
		{
			echo "<p style=\"color:#900\"><i>Please login to view the page you previously requested.<br></i></p><p><br></p>";
		}
		
		if(isset($_POST['submit']))
		{	
			$db = new MySQL();

			// Retrieve username and password from database according to user's input
			$confirm=array();
			$query = "SELECT * FROM users WHERE (username = \"" . $_POST['username'] . "\") AND (password = \"" . md5($_POST['password']) . "\")";
				
			$login = mysql_query($query) or die("Insert query failed: " . mysql_error());
			$confirm=mysql_fetch_array($login , MYSQL_ASSOC);	
			
			// Check username and password match
			if (mysql_num_rows($login) != 0) 
			{
				if($confirm['confirmed']==0)
				{
					$_SESSION['email']=$confirm['email'];
					header('Location: securedpage.php');
					die();
				}
				
				// Jump to secured page
				// Maybe Cookie Instead
				if($_POST['stayloggedin'] == "stayin")
				{
					$expire = time()+60*60*24*30;
					setcookie("username", $_POST['username'], $expire);
				}
				else
				{
					setcookie("username", $_POST['username']);
				}
					$loginfailure = false;
				  
				$db->close();
				header('Location: profile.php');
			}
			else 
			{
			// Jump to login page
			$loginfailure = true;
		  
				$db->close();
			}
		}
			if($loginfailure)
			{
				echo "<font color=red> Login information is incorrect <br></font>";
				$username=$_POST['username'];
				$password="";
			}
			else
			{
				$username="";
				$password="";
			}
		
	

echo "<form method=\"POST\" action=\"login.php\">";
echo "<b>Username</b><br/><input type=\"text\" name=\"username\" id=\"textbox\" size=\"30\" value=\"$username\"><br/>";
echo "<b>Password</b><br/><input type=\"password\" name=\"password\" id=\"textbox\" size=\"30\" value=\"$password\"><br/>";
echo "<input id=\"stayloggedin\" name=\"stayloggedin\"  type=\"checkbox\" value=\"stayin\" /><label for=\"stayloggedin\">Stay logged in?</label></br></br>"; 
echo "<input type=\"submit\" id=\"submit\" value=\"Login\" style=\"margin-left:50px\">";
echo "<input type=\"hidden\" name=\"submit\"></form>";
?>
