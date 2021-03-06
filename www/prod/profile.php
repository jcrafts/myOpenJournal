<?php require('includes/cookiecheck.inc'); 
	require('includes/dbconnect.inc');

  // Inialize session

if(Cookie_IsNotSet() && $_GET['id']==NULL)
{
	header('Location: login.php');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>My Open Journal</title>
    
    <meta name="description" content="My Open Journal" />
	<meta name="keywords" content="my open journal" />
    
    <meta name="author" content="CMPSC 483 W" />
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/universal.css" />
    <link rel="stylesheet" type="text/css" href="css/profile.css" />
    
    <!-- Menu style and javascript -->
    <!--<link type="text/css" href="../../css/menu.css" rel="stylesheet" /> 
    
    <script type="text/javascript" src="../../js/jquery.js"></script>
    <script type="text/javascript" src="../../js/menu.js"></script>-->
	
</head>

<body>

<div id="container">
	<div id="header"><img src="images/blackbanner.png" border="0"/></div>
	
	<div id="nav">
		<?php include("navigation.php"); $user_id=$_GET['id']?>
	</div>

	<div id="content">
		<?php
		if($user_id==NULL)
		{
			$username=$_COOKIE['username'];
			$dbcnx= new MySQL;
			$dbcnx->MySQL();
			$query = "SELECT first_name, last_name, picture
					  FROM users
					  WHERE username = '$username'";
					  
			$result = mysql_query($query);
			if (!$result)   
			{
			echo("<P>Error performing query: " .
				mysql_error() . "</P>");
			exit();
			}
			$info=mysql_fetch_array($result);
			$picture=$info['picture'];
			list($first_name, $last_name)=$info;
			
			?><h2 align="center"><?php
			echo "$first_name  $last_name <a href='editprofile.php'>Edit Profile</a>";
			?></h2><?php
		}
		else
		{
			$dbcnx= new MySQL;
			$dbcnx->MySQL();
			$query = "SELECT first_name, last_name, username, picture
					  FROM users
					  WHERE user_id = '$user_id'";
					  
			$result = mysql_query($query);
			if (!$result)   
			{
			echo("<P>Error performing query: " .
				mysql_error() . "</P>");
			exit();
			}
			$info=mysql_fetch_array($result);
			$picture=$info['picture'];
			$username=$info['username'];
			list($first_name, $last_name)=$info;
			?><h2 align="center"><?php
			echo "$first_name  $last_name";
			?></h2><?php
		}
		?>
		
		<p><br></p>
		
		<div class="picture">
			<?php 
			if ($picture!=NULL)
			{
				echo "<img src='../uploads/avatars/".$picture."' border='0' style=\"max-width:300px; max-height:400px\" align=\"center\" /> ";
				
			}
			else
			{
				echo "<img src=\"http://lcstudentwiki.wikispaces.com/file/view/person.gif/103056049/person.gif\" border=\"0\"/>";
			}
			?>
		</div>
		 
		<div class="information">
			<?php
				$row=array();
				
				$user_info=mysql_query("select * from users where username= '$username'");

				$row=mysql_fetch_array($user_info, MYSQL_ASSOC);
			?>
			<h3><?php
				echo "<h3> Username:</h3><p style=\"margin-left:20px\"> ".$row['username']."</p><br>";
				echo "<h3> Date Joined:</h3><p style=\"margin-left:20px\"> ".$row['join_date']."</p><br>";			
				echo "<h3> E-Mail:</h3><p style=\"margin-left:20px\"> ".$row['email']."</p><br>";
				echo "<h3> Affiliations:</h3><p style=\"margin-left:20px\"> ".$row['affiliations']."</p><br>";
				echo "<h3> Credentials:</h3><p style=\"margin-left:20px\">".$row['credentials']."</p><br>";
			?></h3>
		</div>
		<br/>
				
		<div class="publications">
			<?php	
				
				$user_query;
				$user_info=array();

				$publications_query;
				$publications_info=array();
				
				$document_info=array();
				$document_query;

				$authors_query;
				$authors_list=array();

				$author_query;
				$author_list=array();
				
				$file_query;
				$file_list=array();

				$doc_count=0;

				$user_query=mysql_query("select user_id from users where username='$username'");
				$user_info=mysql_fetch_array($user_query, MYSQL_ASSOC);
				$publications_query=mysql_query("select document_number from authors where user_id=".$user_info['user_id']);
				if (mysql_num_rows($publications_query)==0)
				{
					echo "<p>You have no documents yet. Please upload your research.</p>";
					$uid=$user_info['user_id'];
					//echo "$uid";
				}
				if (mysql_num_rows($publications_query)!=0)
				{
					echo "<h2 align=\"center\">Document Info</h2></br>";
					while($publications_info=mysql_fetch_array($publications_query, MYSQL_ASSOC))//finds all publications the user is associated with
					{	
						$doc_count++;
						$document_query=mysql_query("select * from document where document_number=".$publications_info['document_number']);
						$document_info=mysql_fetch_array($document_query, MYSQL_ASSOC);
						$document_number=$publications_info['document_number'];
						$document_title=$document_info['title'];
						echo "<a href='viewarticle.php?id=".$document_number."'><h3>".$document_title."</h3></a>";
						
						
						
						echo "<p style=\"margin-left:50px\">Rating : ";
						for ($i=0;$i<round($document_info['overall_rating'],0);$i++){
							?><img src="star.png" style="margin-right:10px"/><?php
						}
						echo "</p>";
						echo "<p style=\"margin-left:50px\">Date Published: ".$document_info['published']."</p>";
						echo "<p style=\"margin-left:50px\">Abstract : ".$document_info['abstract']."<br></p>";
						$authors_query=mysql_query("select user_id from authors where document_number=".$document_info['document_number']." and user_id!=".$user_info['user_id']);
						if (mysql_num_rows($authors_query)!=0)
						{
							echo "<p style=\"margin-left:50px\"><br><strong>Co-Authors</strong></p>";
						}
						while($authors_list=mysql_fetch_array($authors_query, MYSQL_ASSOC))//finds all user associated with the document, this excludes the current user
						{
							//info of associated author
							$author_query=mysql_query("select * from users where user_id=".$authors_list['user_id']);
							$author_list=mysql_fetch_array($author_query, MYSQL_ASSOC);
							
							echo "<p style=\"margin-left:75px\">First Name: ".$author_list['first_name']."</p>";
							echo "<p style=\"margin-left:75px\">Last Name: ".$author_list['last_name']."</p>";
							echo "<p style=\"margin-left:75px\">E-Mail: ".$author_list['email']."</p>";
							echo "<p style=\"margin-left:75px\">Affiliations: ".$author_list['affiliations']."";
						}
						$file_query=mysql_query("select * from file where document_number=".$document_info['document_number']);
						if (mysql_num_rows($file_query)!=0)
						{
							echo "<p style=\"margin-left:50px\"><br><strong>File Associated With Document:</strong></p>";
						}
					
						while($file_list=mysql_fetch_array($file_query, MYSQL_ASSOC))//finds all files associates with the document
						{
							//important info of associated file
							echo "<p style=\"margin-left:75px\"><table>";
							echo "<p style=\"margin-left:75px\">Filename : <a href=\"viewarticle.php?id=".$file_list['document_number']."\">".$file_list['filename']."</a></p>";
							echo "<p style=\"margin-left:75px\">Date Uploaded: ".$file_list['date_uploaded']."</p>";
							echo "<p style=\"margin-left:75px\">Under Version: ".$file_list['version']."</p>";
							echo "</table><br/></p>";
						}
					
					
					}/**/
				}
				

				
				/*<p>Publication 1</p>
				<p>Publication 2</p>
				<p>Publication 3</p>
				<p>Publication 4</p>*/
			?>
		</div>
		
		<!--<div class="coauthors">
			<?php
			/*<p>Co-Author 1</p>
			<p>Co-Author 2</p>
			<p>Co-Author 3</p>
			<p>Co-Author 4</p>*/
			$dbcnx->close();
			?>
		</div>-->
	</div>	
	
	
	<?php include("footer.shtml"); ?>
	
</div>

</body>
