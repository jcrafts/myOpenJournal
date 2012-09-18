
<?php include("login_cache.php");
	include("includes/functions.php");
	include("includes/cookiecheck.inc");
	
	// Check, if user is already login, then jump to secured page
	if (isset($_COOKIE['username']))
		$loggedin=true;
	else
		$loggedin=false;


?>


<html>


<?php
	$doc_num = $_GET["id"];                  // The document number of the document being displayed
        $_SESSION['doc_num'] = $doc_num;
	//$doc_num = 12;

	$username=$_COOKIE['username'];
	$dbcnx = @mysql_connect("localhost", "djd5202", "4834life");
	if (!$dbcnx) 
	{
	    echo( "<P>Unable to connect to the " .
		  "database server at this time.</P>" );
	    exit();
	}


	mysql_select_db("myopenjournal", $dbcnx);


	// ------------------------------------------Document Table---------------------------------------------------   
	//	document_number int not null auto_increment,                  /*document number is a primary key*/
	//	document_type varchar(7),          /* .pdf, .ps, .png, .jpeg*/
	//	document_name varchar(255),   	  /*name of document/Title */
	//	overall_rating smallint,	          /*from 0 to 10*/
	//	published date,			  /*date published*/
	//	title varchar(40),		  /*title of document typically what its about*/
	//	number_of_versions smallint,	  /*number of versions uploaded*/
	//	main_document_number int,		  /*doc number of parent doc, points to itself if root*/
	//	description  text,
	// ----------------------------------------------------------------------------------------------------------
	//these need to be finished, page_author should contain the user's name, not his id (use current values in more queries)
	//

	// Get Article information

	$page_query = mysql_query("SELECT * FROM document D WHERE D.document_number = '" . $doc_num. "'") or die(mysql_error());
	$page_info = mysql_fetch_array($page_query);
	$page_title = $page_info["title"];
	$page_publish_date = $page_info["published"];
	$page_version = $page_info["number_of_versions"];
	$page_description = $page_info["abstract"];
	$page_type = $page_info["document_type"];
	$page_name = $page_info["document_name"];

?>


<head>    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>My Open Journal</title>
    
    <meta name="description" content="My Open Journal" />
	<meta name="keywords" content="my open journal" />
    
    <meta name="author" content="CMPSC 483 W" />
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" type="text/css" href="css/universal.css" />
    <link rel="stylesheet" type="text/css" href="css/page.css" />
    <link type="text/css" rel="stylesheet" href="css/viewarticle.css" />
    
    <!-- Menu style and javascript -->
    <!--<link type="text/css" href="css/menu.css" rel="stylesheet" /> 
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/menu.js"></script>-->

    <!--required for commenting system-->
    <?php $ACS_path = "advanced_comment_system/"; ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $ACS_path; ?>css/style.css" />
    
    <script src="<?php echo $ACS_path; ?>js/common.js" type="text/javascript"></script>
    <script src="<?php echo $ACS_path; ?>js/mootools.js" type="text/javascript"></script>


</head>

<body onload="ACS_init();">

<!-- Facebook "Like" Javascript-->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<div id="container">
	
	<div id="header"><img src="images/blackbanner.png" border="0"/></div>
	
	<div id="nav">
		<?php 
			include("navigation.php");
		        
		?>
	</div>

	<div id="body_content">
	<div id="content">
		<h2 align="center"><?php echo $page_title ?></h2>
		<p><br></p>

        <center>
        <table>      
         <tr>

		 <td id="preview">
                 <?php

                   include ("includes/article_functions.php") ;

                    $document_type = getFileType($doc_num);
                    $document_name = getFileName($doc_num);
                    $document_files = getSupportingFiles($doc_num);
                    $document_version = getVersionNum($doc_num);
		 
		    updateOverallRating($doc_num);

                    if( strlen(strpos($document_type,"doc")) > 0)
                    {
                         $image_src="images/word.png";
                    }
                    else if ($document_type == "pdf")
                    {
                         $image_src="images/pdf.png";
                    }
		    else if ($document_type == "ppt")
                    {
                         $image_src="images/ppt-81.png";
                    }
		    else if ($document_type == "txt")
                    {
                         $image_src="images/txt-109.png";
                    }
		    else if ($document_type == "c")
                    {
                         $image_src="images/C.png";
                    }
		    else if ($document_type == "cpp")
                    {
                         $image_src="images/cpp-159.png";
                    }
		    else if ($document_type == "java")
                    {
                         $image_src="images/java-278.png";
                    }
                    else if ($document_type == "png" || $document_type == "jpeg")
                    {
                         $image_src="/uploads/$doc_num/$document_name";
                    }
                    else
                    {
                         $image_src="images/noimage.png";
                    }
			
echo<<<HTML
                       <a href='/uploads/$doc_num/$document_name'>
		        <img src="$image_src" alt="" title="Click To View" width="200" height="200" class="floatRightClear" />

                        </a> 
                 
HTML
                  ?>                  
                </td>    

                        

		
                
                <td>
		<?php
				
			//  Display Document information	
			echo "<br>Rating: "; printOverallRating($doc_num);echo "<br ><br >";
                        echo "Author(s): "; printAuthorsNames($doc_num); echo "<br />"; 
			echo "Date Published: ".$page_publish_date."<br />";
			echo "Version: ".$document_version."<br />";			
			echo "Categories: "; printCategories($doc_num); echo "<br />";
			echo "<a href='/uploads/".$doc_num."/".$document_name."'>View Document</a><br /><br />";
			echo "Description: ".$page_description."<br /><br/>";


		?>
		  

                </td>   
   
                <td>

     
                </td>            





          </tr>


          </table>
           
	           <?php  
			echo "<fieldset style='width: 600px'>";
			echo	" <legend>Attachments</legend>";
		    	echo	" <b> ";getFilesLinks($doc_num); echo" </b>";
		  	echo "</fieldset>";
			mysql_close($dbcnx);
		?>
            <br> <br>
            <span id="like" style="display:inline;">
                <legend class="info"> Like it:  </legend> 
		<g:plusone annotation="none"></g:plusone>

		<!-- Place this render call where appropriate -->
		<script type="text/javascript">
		  (function() {
		    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		    po.src = 'https://apis.google.com/js/plusone.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
		<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://p117inst10.cse.psu.edu/prod/viewarticle.php?id=<?php echo $doc_num;?>" 
                   data-text="Checking Out this page about <?php echo $document_name;?> on myOpenJournal!" data-count="horizontal">Tweet</a>
		<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
		<div class="fb-like" data-href="http://p117inst10.cse.psu.edu/prod/viewarticle.php?id=<?php echo $doc_num;?>" data-send="true" data-width="150" data-show-faces="true"></div>
	    </span>
           <br>
	   <br>
	   <br>
	   <div id="share">
		        <legend class="info"> Share it:  </legend> 
			<span  class='st_facebook_large' ></span>
			<span  class='st_twitter_large' ></span>
			<span  class='st_stumbleupon_large' ></span>
			<span  class='st_digg_large' ></span>
			<span  class='st_linkedin_large' ></span>
			<span  class='st_myspace_large' ></span>
			<span  class='st_wordpress_large' ></span>
			<span  class='st_yahoo_large' ></span>
			<span  class='st_email_large' ></span>
		        <script type="text/javascript">var switchTo5x=true;</script>
		        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
		        <script type="text/javascript">stLight.options({publisher:'1cd7ed92-f62a-411b-b69e-c08a80b768fc'});</script>
            </div>	
		
            </center>



	<div id="review">
           <?php include($ACS_path."index.php"); ?>
	</div>
	</div>
	</div>      
        
        
	<?php include("footer.shtml"); ?>

</div>


</body>

</html>
