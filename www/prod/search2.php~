<?php require('includes/cookiecheck.inc'); 
	require('includes/dbconnect.inc');

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
		<?php include("navigation.php");?>
	</div>

	<div id="body_content">
	<div id="content">
		<h2>Results</h2>

		<?php
			$dbcnx= new MySQL;
			$dbcnx->MySQL();

			if($loggedin)
			  {
				$username=$_COOKIE['username'];

				$query = "SELECT first_name, last_name
						  FROM user
						  WHERE username = '".mysql_real_escape_string($username)."'";
						  
				$result = mysql_query($query);
				if (!$result)   
				  {
				echo("<P>Error performing query: " .
					 mysql_error() . "</P>");
				exit();
				  }
				$info=mysql_fetch_array($result);
				list($first_name, $last_name)=$info;
				
				echo "Hello $first_name  $last_name, you are currently logged in as user ".$username."<BR>";

				echo "click here to <a href='logout.php'>logout</a>";
			  }
			?>

			<?php
			function recurseCategories($fillCatArrays)
			{
				$fillCatArrays = populateCategories($fillCatsArray, "0");
				return $fillCatArrays;
			}

			function populateCategories($fillCatArrays, $checkCat)
			{
				$catIndex = intval($checkCat);
				if($checkCat == "0")
				{
					$fillList = 0;
					$queBaseCats = mysql_query("SELECT * FROM category_names WHERE category_id != parent_id AND parent_id = 0");
					while($row = mysql_fetch_array($queBaseCats)) 
					{
						$fillCatArrays[$catIndex][$fillList] = $row['category_id'];
						$fillList++;
			 			$fillCatArrays = populateCategories($fillCatArrays, $row['category_id']);
					}
				}
				else
				{
					$fillList = 0;
					$queBaseCats = mysql_query("SELECT * FROM category_names WHERE parent_id = " . $checkCat);
					while($row = mysql_fetch_array($queBaseCats)) 
					{
						$fillCatArrays[$catIndex][$fillList] = $row['category_id'];
						$fillList++;
						$fillCatArrays = populateCategories($fillCatArrays, $row['category_id']);
					}
				}
				return $fillCatArrays;
			}

			function addRecurseCats($category_array, $catArr, $category_id)
			{
				$cur_array = $catArr[$category_id];
				if(is_array($cur_array))
				{
					foreach($cur_array as $new_category_id)
					{
						$category_array = addRecurseCats($category_array, $catArr, $new_category_id);
						array_push($category_array, $new_category_id);
					}
				}
				return $category_array;
			}

			function startSearch()
			{
			  $make_query = "";
			  /*
			  if(empty($search_title) && empty($search_keywords) && empty($category_select))
			  {
				
			  }
			  */
			  $search_tables = "document D";
			  $document_where = "";
			  $ending_where = "";
			  $search_title = mysql_real_escape_string($_POST["search_title"]);
			  $search_keywords = mysql_real_escape_string($_POST["search_keywords"]);
			  $category_select = rtrim($_POST["add_categories"]);
			  $category_string = "";
			  //echo "Category Select: " . $category_select . "<br /><br />";
			  if(!empty($category_select))
			  {
				$catArr = array();
				$catArr = recurseCategories($newArr);
				//echo "Print Recursion: <br /><br />";
				//print_r($catArr);
				$category_array = explode(" ", $category_select);
				$cat_copy = explode(" ", $category_select);
				foreach($cat_copy as $category_id)
				{
					$category_array = addRecurseCats($category_array, $catArr,  $category_id);
				}
				$category_array = array_unique($category_array);
				//echo "<br /><br />NEW CATEGORY ARRAY: <br /><br />";
				//print_r($category_array);
				$first_cat_entry = true;
				$category_string .= "(";
				foreach($category_array as $category_id)
				{
					if(!$first_cat_entry)
					{
						$category_string .= " OR ";
					}
					$category_string .= "C.category_id = '".$category_id."'";
					$first_cat_entry = false;
				}
				$category_string .= ")";
			  }
			  if(!empty($search_title))
				{
				  $document_where .= "D.title = '".$search_title."'";
				  //$make_query = "SELECT * FROM " . $search_tables . " WHERE " . $ending_where;
				}
			  //echo "CAT ".$category_select."<br />";
			  if(!empty($search_keywords))
			  {
				  //$search_tables .= ", tags T";
				  $search_terms = explode(" ", $search_keywords);
				  
				  if(!empty($search_terms))
				{
	
				  
				
				  $document_where .= "(";
				  $first_search_entry = true;
				  foreach($search_terms as $cur_keyword)
				  {
					  //echo $cur_keyword . " ";
					  if(!$first_search_entry)
					{
					  $document_where .= " OR ";
					}
					  $document_where .= "D.title LIKE '%".$cur_keyword."%' OR D.abstract LIKE '%".$cur_keyword."%'";
					  $first_search_entry = false;
			          }
				  $document_where .= ")";
				}
				
			   }
			
			  if(!empty($document_where))
			  {
				  $make_query .= " WHERE " . $document_where;
			  }
		
		
			  if(!empty($category_string))
			  {
				if(!empty($search_tables))
				{
					$search_tables .= ", ";
				}
				$search_tables .= "categories C";
				if(!empty($search_tables))
				{
					$category_string = "(D.document_number = C.document_number) AND " . $category_string;
				}
				if(!empty($document_where))
				{
					$category_string = " AND " . $category_string;	
				}
			  }
			
			  //echo $search_tables . "<br /><br />";
			  $make_query = "SELECT * FROM " . $search_tables;
			  if(!empty($document_where) || !empty($category_string))
			  {
				$make_query .= " WHERE ";
			  }
			  $make_query .= $document_where;
			  $make_query .= $category_string;
			  //echo "<br /><br />Make query: " . $make_query."<br /><br />";
		
			  //$search_tables
			//echo "Hush";
			  executeSearch($make_query, $category_string);
			}

			function executeSearch($search_query, $category_string)
			{
			  //echo "The cat string is: " . $category_string . "<br /><br />";
			  if(empty($search_query))
				{
				  echo "<b>No search results found</b>";
				  return;
				}
			  $username=$_SESSION['username'];
			 
			  $exec_search = mysql_query($search_query) or die(mysql_error());
			  $results = mysql_num_rows($exec_search);
			  if($results == 0)
			  {
				  echo "<b>No search results found</b>";
				  return;
			  }
			  echo "<b>" . $results . "</b>" . " results found: <br /><br />";
			  while($search_array = mysql_fetch_array($exec_search))
			   {
			      //print_r($search_array);
			      echo "<a href=\"viewarticle.php?id=".$search_array["document_number"]."\">".$search_array["title"]."</a><br />";
			   }
			  /*
			  //echo "<b>" . $results . "</b>" . " results found: <br /><br />";
			  $search_results = array();
			  while($search_array = mysql_fetch_array($exec_search))
			  {
				  $doc_num = $search_array["document_number"];
				  $ending_check = $category_string . " AND (document_number = '$doc_num')";
				  echo "Category SQL : " . $ending_check . "<br /><br />";
				  $category_check = mysql_query($ending_check) or die(mysql_error());
				  $cat_results = mysql_num_rows($category_check);
				  if($cat_results != 0)
				  {
				  	array_push($search_results, "<a href=\"viewarticle.php?id=".$search_array["document_number"]."\">".$search_array["title"]."</a><br />");
				  }
				  
			  }
			  	echo "ELEMENTS: " . count($search_results);
			*/
			}

			startSearch();
			$dbcnx->close();
		?>
		
	</div>
	</div>
	
	<?php include("footer.shtml"); ?>

</div>
	
</body>

</html>
