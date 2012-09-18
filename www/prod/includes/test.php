<?php




			echo " ddi". getSupportingFiles($docnum);
			echo printSupportingFiles($docnum);

include("includes/article_functions.php");

$docnum=12;

echo "dfdfdf" ; 
getSupportingFiles($docnum);
 $document_name = getFileName($docnum);
echo $document_name;
print_r($document_name);

print_r(getSupportingFiles($docnum));
?>
