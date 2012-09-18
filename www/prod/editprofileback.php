<?php
   
    session_start();

	
	include_once('includes/dbconnect.inc');
	$db = new MySQL();

        unset($_SESSION['editprofileFail']);
        unset($_SESSION['fnameTextFail']);
        unset($_SESSION['lnameTextFail']);
        //unset($_SESSION['unameNullFail']);
        unset($_SESSION['passwordfail']);
        //unset($_SESSION['']);
       
      
        $registerFail = false;
       
        if($_POST["first_name"] == Null  || strlen($_POST['first_name'])<2)
        { 
            $_SESSION['fnameTextFail'] = true;
            $_SESSION['editprofileFail'] = true;
        }
     
        if($_POST["last_name"] == Null || strlen($_POST['last_name'])<2 )
        { 
            $_SESSION['lnameTextFail'] = true;
            $_SESSION['editprofileFail'] = true;
        }

	if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $_POST['email'])) 
	{ 					
	    $_SESSION['emailFail'] = true;
            $_SESSION['editprofileFail'] = true;			
	}
   
	/*
        if($_POST["username"] == Null || ctype_alnum($_POST['username'])==FALSE)
        { 
            $_SESSION['unameNullFail'] = true;
            $_SESSION['editprofileFail'] = true;
        }
	*/
        
	/*
        if (ctype_alnum($_POST['password'])==FALSE)
	{
            $_SESSION['passwordfail'] = true; 
            $_SESSION['registerFail'] = true; 
	}
	*/
			if( empty($_POST['password']) && !empty($_POST['re_password']))
		    {
					$_SESSION['passwordfail'] = true; 
					$_SESSION['editprofileFail'] = true; 
			}

        if ((!empty($_POST['password'])) && (($_POST['password']!=$_POST['re_password']) || (ctype_alnum($_POST['password'])==FALSE)))
		{
            $_SESSION['passwordfail'] = true; 
            $_SESSION['editprofileFail'] = true; 
			echo "password failed";
		}


        if(isset($_SESSION['editprofileFail']) )
        { 
            header('Location: editprofile.php');            
        }
		else
        {
	    	if(isset($_COOKIE['username']))
		{
			$uname = $_COOKIE['username'];
		}
		else
		{
			$uname = $_SESSION['username'];
		}	
                 //insert new user entry into users table
		    /*
		    $query = "INSERT INTO users (username, password, join_date, credentials, email, first_name, last_name,affiliations)
		    			values(\"" . $_POST["username"] . "\", 
                                                \"" . md5($_POST["password"]) . "\", 
                                                \"" . date('Y-m-d') . "\", 
                                                \"" . $_POST["credentials"] . "\", 
                                                \"" . $_POST["email"] . "\", 
                                                \"" . $_POST["first_name"] . "\", 
                                                \"" . $_POST["last_name"] . "\",
                                                \"" . $_POST["affiliation"] . "\")";
	            mysql_query($query) or die("Insert query failed: " . mysql_error());
		    */
		
		    $result = mysql_query("UPDATE users SET credentials = \"" . $_POST["credentials"] . "\", email = \"" . $_POST["email"] . "\", first_name = \"" . $_POST["first_name"] . "\", last_name = \"" . $_POST["last_name"] . "\", affiliations = \"" . $_POST["affiliation"] . "\" WHERE username = \"" . $uname . "\"") or die(mysql_error());  

		    if (!empty($_POST['password']) )
		    {
				if( !empty($_POST['re_password']))
				{	
					if($_POST['re_password'] == $_POST['password'])
					{
						$new_password=md5($_POST["password"]);
						$result = mysql_query("UPDATE users SET password = '$new_password' WHERE username='$uname'") or die("sup ar jay");//(mysql_error());  
					}
					else
					{
						$_SESSION['passwordfail'] = true; 
						$_SESSION['editprofileFail'] = true; 
						header('Location: editprofile.php'); 
					
					}
				}
				else
				{
					$_SESSION['passwordfail'] = true; 
					$_SESSION['editprofileFail'] = true; 
					header('Location: editprofile.php'); 
				}
			}

			
		   $picture_full_name = $_FILES['picture']['name'];
		    if(!empty($picture_full_name))
		    {
				$query_user_id = mysql_query("SELECT user_id FROM users WHERE username = '$uname'") or die(mysql_error());
				$arr_user_id = mysql_fetch_assoc($query_user_id);
				$user_id = $arr_user_id['user_id'];
		    	$picture_ext = pathinfo($picture_full_name, PATHINFO_EXTENSION);
				$picture_name = $user_id . "." . $picture_ext;
		    	$target_path = "/var/www/html/uploads/avatars/" . $picture_name;
		   }
		   if(move_uploaded_file($_FILES['picture']['tmp_name'], $target_path)) 
		   {
			$result = mysql_query("UPDATE users SET picture = \"".$picture_name."\" WHERE user_id=".$user_id) or die(mysql_error());
			header('profile.php');
		   }
		   
		   if(isset($_SESSION['editprofilefail']))
		   unset($_SESSION['editprofilefail']);

	    	   header('Location: index.php');

       }

	$db->close();
?>
