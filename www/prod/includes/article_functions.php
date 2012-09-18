           


<?php

$username=$_COOKIE['username'];
$dbcnx = @mysql_connect("localhost", "djd5202", "4834life");
if (!$dbcnx) 
{
	echo( "<P>Unable to connect to the " .
	"database server at this time.</P>" );
	 exit();
}


	mysql_select_db("myopenjournal", $dbcnx);


// returns this documents file type (i.e. pdf, doc)
function getFileType($doc_num)
{
	$files_query = mysql_query("SELECT filename FROM file F WHERE F.document_number = '" . $doc_num. "' and ismain=1") or die(mysql_error());
			       
        $fname = mysql_fetch_assoc($files_query);
	$fname = $fname['filename'];
                               
	$file_type = pathinfo($fname, PATHINFO_EXTENSION);
        
                                
	return $file_type;
}

// returns this documents orignial name (i.e. filename.pdf)
function getFileName($doc_num)
{
	$files_query = mysql_query("SELECT filename FROM file F WHERE F.document_number = '" . $doc_num. "' and ismain=1") or die(mysql_error());
			       
        $fname = mysql_fetch_assoc($files_query);
	$fname = $fname['filename'];
                                
	return $fname;
}

                     
function getAuthorsNames($doc_num)
{
	$authorsArrays= array();
	$$users_info = array();
        $numOfAuthors = 0;
	$authors_query = mysql_query("SELECT * FROM authors A WHERE A.document_number = '" . $doc_num. "'") or die(mysql_error());
			       
	 while($row = mysql_fetch_array($authors_query)) 
	{		
		$users_query = mysql_query("SELECT * FROM users U WHERE U.user_id = " . $row["user_id"]) or die(mysql_error());
		$users_info = mysql_fetch_array($users_query, MYSQL_ASSOC);
		$authorsArrays[$numOfAuthors] = $users_info;
		$numOfAuthors++;
	}
	

	return $authorsArrays;
}

// echos a list of this document's authors
function printAuthorsNames($doc_num)
{
	$authorArrays = getAuthorsNames($doc_num);
	echo "<table>";
	 for($count=0; $count <= sizeof($authorArrays)-1; $count++)
	{ 
		echo "<tr><a href='profile.php?id=".$authorArrays[$count]['user_id']."'>".$authorArrays[$count]['first_name']." ". $authorArrays[$count]['last_name']."</a></tr>";

	}			       
	echo "</table>";
}

// returns an array of every category of this document falls under
function getCategories($doc_num)
{
	$numOfAuthors = 0;
        $categoriesArrays = array();
	$categories_query = mysql_query("SELECT * FROM categories A WHERE A.document_number = '" . $doc_num. "'") or die(mysql_error());
			       
	while($row = mysql_fetch_array($categories_query)) 
	{		
		$category_names_query = mysql_query("SELECT * FROM category_names U WHERE U.category_id = " . $row["category_id"]) or die(mysql_error());
		$category_names_info = mysql_fetch_array($category_names_query);
		$categoriesArrays[$numOfAuthors] = $category_names_info["category_name"];
		$numOfAuthors++;
	}

	return $categoriesArrays;
}

// echos a list of this document's categories
function printCategories($doc_num)
{
	$categories = getCategories($doc_num);

	for($count=0; $count < sizeof($categories); $count++)
	{ 
		echo " " .$categories[$count]. ", ";
	}
			       
}

// returns the version number of the main document
function getVersionNum($doc_num)
{

        $version_num=0;
	$files_query = mysql_query("SELECT * FROM file F WHERE F.document_number = '" . $doc_num. "' and ismain=1") or die(mysql_error());
		       
	$file_info = mysql_fetch_array($files_query);
	
        $version_num= $file_info['version'];        

	return ($version_num);
}


// returns the overall rating for this article
function updateOverallRating($doc_num)
{
	$overall_rating = getCalculateOverallRating($doc_num);
         mysql_query("UPDATE document SET  overall_rating = $overall_rating WHERE document_number = $doc_num") or die(mysql_error());
	

}



// returns the overall rating for this article
function getOverallRating($doc_num)
{
	$article_query = mysql_query("SELECT * FROM document D WHERE D.document_number = '" . $doc_num. "'") or die(mysql_error());
	$article_info = mysql_fetch_array($article_query);

	$rating= $article_info['overall_rating']; 

	return ($rating);
}

// returns an array of every ratings for this article
function getRatings($doc_num)
{

        $rating_num=0;
        $ratings = array();
	$review_query = mysql_query("SELECT * FROM review R WHERE R.document_number = '" . $doc_num. "' and parent_id=0") or die(mysql_error());
		       

	while ($row = mysql_fetch_array($review_query))
	{
             $ratings[$rating_num] = $row['rating'];
             $rating_num++;
        }

   

	return ($ratings);
}

// returns the calculates the overall rating for this article
function getCalculateOverallRating($doc_num)
{

	$ratings = getRatings($doc_num);
        $sum=0;
	$total=0;

	$size = sizeof($ratings);

	for($count=0; $count < $size; $count++)
	{ 
		$sum+=$ratings[$count];
	}
    
	if($sum != NULL)
	{
	$total=$sum/$size;
	}
	else
	{
	$total=0;
	}

	return ($total);
}



// echos the overall rating for this article
function printOverallRating($doc_num)
{

	//echo "".getOverallRating($doc_num)." / 5";
	
	for ($i=0;$i<round(getOverallRating($doc_num),0);$i++){
		echo "<img src='star.png' align='right' style='margin-right:10px'/>";
	}
        
}


// returns an array of every file associated with the document number $doc_num
function getSupportingFiles($doc_num)
{
        $numOfFiles = 0;
        $filesArray = array();
	$files_query = mysql_query("SELECT * FROM file F WHERE F.document_number = '" . $doc_num. "' and ismain=0") or die(mysql_error());
		       
	while ($row = mysql_fetch_array($files_query))
	{
             $filesArray[$numOfFiles] = $row['filename'];
             //echo $filesArray[$numOfFiles]."<br>" ;
             $numOfFiles++;
        }

        //print_r($filesArray);

	return ($filesArray);
}

// echos a list of every file associated with the document number $doc_num
function printSupportingFiles($doc_num)
{

        $files = getSupportingFiles($doc_num) ;

	$size = sizeof($files);

	for($count=0; $count <= $size; $count++)
	{ 
		echo " " .$files[$count]. "<br> ";
	}

}

// echos a list of every file associated with the document number $doc_num
function getFilesLinks($doc_num)
{

        $files = getSupportingFiles($doc_num) ;

	$size = sizeof($files);
  
        if( $size > 0 )
        {
		for($count=0; $count < $size; $count++)
		{ 
			echo " <a href='/uploads/$doc_num/$files[$count]'>" .$files[$count]. "</a>";
			//echo "<br>";
		}
        }
        else
        {
		echo " No Attachments Available";
        }

}

?>
