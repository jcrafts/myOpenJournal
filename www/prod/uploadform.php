	<script type="text/javascript">
	function addSelectedChild(parentSelect)
	{
		var parent_index = parentSelect.options[parentSelect.selectedIndex].value;
		var root_next = parentSelect.nextSibling;
		while(root_next)
		{
			next_root = root_next.nextSibling;
			root_next.parentNode.removeChild(root_next);
			root_next = next_root;
		}
		if(parent_index != "")
		{
			var correspondingSelect = document.getElementById('catlist_' + parent_index);
			var cloneNewParent = correspondingSelect.cloneNode(true);
			cloneNewParent.id = "";
			//cloneNewParent.name = "addCatSelect[]";
			var textspace = document.createTextNode(' ');
			parentSelect.parentNode.appendChild(textspace);
			parentSelect.parentNode.appendChild(cloneNewParent);
		}
	}

	function addNewCategorySelect(currentNode, parent_index)
	{
		var correspondingSelect = document.getElementById('catlist_' + parent_index);
		var cloneNewParent = correspondingSelect.cloneNode(true);
		cloneNewParent.id = "";
		currentNode.appendChild(document.createTextNode(' '));
		currentNode.appendChild(cloneNewParent);
	}
	</script>	


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
				$fillCatArrays[$catIndex][$fillList] = "<option value=\"".$row['category_id']."\">".$row['category_name']."</option>";
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
				$fillCatArrays[$catIndex][$fillList] = "<option value=\"".$row['category_id']."\">".$row['category_name']."</option>";
				$fillList++;
		                $fillCatArrays = populateCategories($fillCatArrays, $row['category_id']);
			}
		}
		return $fillCatArrays;
	}

	$catArr = array();
	$catArr = recurseCategories($newArr);

	echo "<div style=\"display: none\">";
	foreach(array_keys($catArr) as $i)
	{
		$opt_count = count($catArr[$i]);
		if($opt_count != 0)
		{
			echo "<select id=\"catlist_".$i."\" onchange=\"addSelectedChild(this)\">";
			$opt_count = count($catArr[$i]);
			echo "<option value=\"\">Select Category</option>";
			foreach(array_keys($catArr[$i]) as $s)
			{
				echo $catArr[$i][$s];
			}
			echo "</select>";	
		}
	}
	echo "</div>";
	?>


		<h2 align="center">Upload</h2>
		<p><br></p><form id='upload_frm' enctype="multipart/form-data" name='upload_frm' onsubmit="saveUploadForm()" action='uploadarticle2.php' method='post'>
		<input type='hidden' id='phase' name='phase' value='submit' />
			<p>
				<label for='document_title'>Journal Title</label>
<?php
				echo "<input type='text' name='document_title' id='document_title' value='$document_title'/>";
?>
			</p>
			<p>

				<br />

				<div id="additional_cats">
				<div id="div_cat_main">Main Category: </div>
				<script type="text/javascript">
				var catDiv = document.getElementById("div_cat_main");
				addNewCategorySelect(catDiv, 0);
				</script>							
				</div>

				<script type="text/javascript">
				var addCat = 1;
				function addCategory()
				{
					var addCats = document.getElementById('additional_cats');
					var newDivCat = document.createElement('div');
				        newDivCat.id = 'div_cat_'+addCat;
					var catText = document.createTextNode('Secondary Category: ');
					var removeLink = document.createElement('a');
					removeLink.href = "javascript:void(0)";
					removeLink.onclick = function()
					{
						removeElement(this);
					}
					var linkText = document.createTextNode('Remove');
					removeLink.appendChild(linkText);
				        newDivCat.appendChild(catText);
					newDivCat.appendChild(removeLink);
					addCats.appendChild(newDivCat);
					var catDiv = document.getElementById("div_cat_" + addCat);
					addNewCategorySelect(catDiv, 0);
					addCat++;
				}	
			        function removeElement(ele)
				{
					ele.parentNode.parentNode.removeChild(ele.parentNode);
				}
				function addMoreFiles()
				{
					var add_files_div = document.getElementById('add_files_div');
					var new_files_div = document.createElement('div');
					var new_file_input = document.createElement('input');
					new_file_input.name = "additionalfiles[]";
					new_file_input.type = "file";
					var space = document.createTextNode(" ");
					var removeLink = document.createElement('a');
					removeLink.href = "javascript:void(0)";
					removeLink.onclick = function()
					{
						removeElement(this);
					}
					var linkText = document.createTextNode('Remove');
					removeLink.appendChild(linkText);
					new_files_div.appendChild(removeLink);
					new_files_div.appendChild(space);
					new_files_div.appendChild(space);
					new_files_div.appendChild(new_file_input);
				        add_files_div.appendChild(new_files_div);
				}
				function addMoreAuthors()
				{
					var add_authors_div = document.getElementById('add_authors_div');
					var new_authors_div = document.createElement('div');
					var new_authors_input = document.createElement('input');
					new_authors_input.name = "authors[]";
					new_authors_input.type = "text";
					var space = document.createTextNode(" ");
					var removeLink = document.createElement('a');
					removeLink.href = "javascript:void(0)";
					removeLink.onclick = function()
					{
						removeElement(this);
					}
					var linkText = document.createTextNode('Remove');
					removeLink.appendChild(linkText);
					new_authors_div.appendChild(removeLink);
					new_authors_div.appendChild(space);
					new_authors_div.appendChild(new_authors_input);
				        add_authors_div.appendChild(new_authors_div);
				}
				function saveUploadForm()
				{
					var addCats = document.getElementById('additional_cats');
					var addDivs = addCats.getElementsByTagName('div');
					var add_str = "";
					for(d = 0; d < addDivs.length; d++)
					{
						var div_sel = addDivs[d].getElementsByTagName('select');
						var index_sel = div_sel.length - 1;
						var row_sel = div_sel[index_sel];
						if(row_sel.options[row_sel.selectedIndex].value != "")
						{
							add_str += row_sel.options[row_sel.selectedIndex].value + ' ';
						}
						else if(index_sel > 0)
						{
							index_sel--;
							var row_sel = div_sel[index_sel];
							add_str += row_sel.options[row_sel.selectedIndex].value + ' ';							
						}
					}
					document.upload_frm.add_categories.value = add_str;
				}
				</script>	

				<input type="button" onclick="addCategory()" value="Add Category" />
				<input type="hidden" name="add_categories" />
				

				
			<p>
				<label for='description'>Description of the File(Abstract, etc.)</label>
			<?php
				echo "<br><textarea rows = \"5\" cols = \"75\" name=\"description\" wrap=\"physical\"></textarea><br>";
			?>
			</p>
					
				<p>
					Choose a file to upload: <input name="uploadedfile" type="file" /><br />
				</p>

		
		<div id='supportingfiles'>
		
		
			<p>
				Click here to upload any additional files: <input name="additionalfiles[]" type="file" /><br />
				<div id="add_files_div"></div>
				<input type="button" value = "Attach More Files" onclick="addMoreFiles()" /> 
				<br />
				<br />
				Co-Author Usernames: <input type="text" name="authors[]" />
				<br />
				<div id="add_authors_div"></div>
				<input type="button" value = "Add More Authors" onclick="addMoreAuthors()" />
				<br />
				<br /> 		
				<input type="submit" value = " Submit Journal " />
			</p>
		</div>

		<p>	
		</p>
	   </form>
       

	</div>

