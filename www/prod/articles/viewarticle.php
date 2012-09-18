<?php

// Inialize session
session_start();

if (isset($_SESSION['username']))
  $loggedin=true;
else
    $loggedin=false;

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
			if (isset($_COOKIE['username'])) {
				include("navigation_logged.shtml");
			} else {
				include("navigation.shtml");
			}
		?>
	</div>

	<div id="body_content">
	<div id="content">

		<?php

			$doc_num = $_GET["id"];

			$username=$_SESSION['username'];
			$dbcnx = @mysql_connect("localhost", "djd5202", "4834life");
			if (!$dbcnx) 
			{
				echo( "<P>Unable to connect to the " .
					  "database server at this time.</P>" );
				exit();
			}

			mysql_select_db("myopenjournal", $dbcnx);

			$page_query = mysql_query("SELECT * FROM document D WHERE D.document_number = '" . $doc_num. "'") or die(mysql_error());
			$page_info = mysql_fetch_array($page_query);
			$page_title = $page_info["title"];
			$page_publish_date = $page_info["published date"];
			$page_version = $page_info["version"];
			$page_author = $page_info["user_id"];
			$page_category = $page_info["category_id"];
			$page_description = $page_info["description"];
			$page_type = $page_info["document_type"];
			$page_name = $page_info["document_name"];

			//document_number int not null auto_increment,                  /*document number is a primary key*/
				//category_id int,                   /*tag_id*/
			  //  document_type varchar(7),          /* .pdf, .ps, .png, .jpeg*/
			  //  document_name varchar(255),   	  /*name of document/Title */
			   // overall_rating smallint,	/*from 0 to 10*/
			   // published date,				/*date published*/
			   // title varchar(40),			/*title of document typically what its about*/
			   // number_of_versions smallint,		/*number of versions uploaded*/
			   // user_id int,				/*user id of owner, foreign key from users*/
			   // main_document_number int,				/*doc number of parent doc, points to itself if root*/
			   // description  text,
			   
			   
			   
			   //these need to be finished, page_author should contain the user's name, not his id (use current values in more queries)
			   //
			   mysql_close($dbcnx);
		?>

			<head>
			<title><?php echo $page_title; ?></title>
			</head>

			<body>


		<?php
			echo "<h2>".$page_title."</h2>";
			echo "<br />Published: ".$page_publish_date."<br />";
			echo "Version: ".$page_version."<br />";
			echo "Author: ".$page_author."<br />";
			echo "Category: ".$page_category."<br />";
			echo "<a href='/uploads/".$page_name."'>View Document</a><br /><br />";
			echo "Description: ".$page_description."<br /><br/>";
		?>
		  <fieldset style="width: 500px;">
			<legend>Attachments</legend>
			 <b>No Attachments</b>
		  </fieldset>

		<BR><BR>
		<a href="../index.php">Click here to return to the homepage </a>
		
	</div>
	</div>
	
	<div id="footer">
		<p><br><br>Copyright of the MyOpenJournal team in CMPSC 483W Fall 2011</p>
	</div>

</div>
	
</body>

</html>