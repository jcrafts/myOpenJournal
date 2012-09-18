<?php
/* generates a $length-digit code */
function ACS_generateAntiSpamCode($length){
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz0123456789";
	$code = "";
	srand((double)microtime()*1000000);

	for($i=0;$i<$length;$i++){
		$num = rand()%33;
		$char = substr($chars,$num,1);
		$code .= $char;
	}

	return $code;
}

/* converts links in text to html-links */
function ACS_convertLinks($text){
	$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)','<a href="\\1" target="_blank">\\1</a>',$text);
	$text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)','\\1<a href="http://\\2" target="_blank">\\2</a>',$text);
	$text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})','<a href="mailto:\\1">\\1</a>',$text);
	
	return $text;
}

/* prepare inserts */
function ACS_prepareInsert($text){
	$text = addslashes($text);
	$text = str_replace("<","&lt;",$text);
	$text = str_replace(">","&gt;",$text);
	$text = ACS_convertLinks($text);
	$text = str_replace("\n","<br />",$text);
	
	return $text;
}

/*recursively print children comments*/
//parent_id = 0 means that comment is reply to document
function print_children_comments($ACS_path, $ACS_CONFIG, $doc_num, $parent_id, $recurse_depth)
{
   //get data from database
   $ACS_ord = $ACS_CONFIG["comments_order"] == "top" ? "DESC" : "ASC";
   $ACS_con = @mysql_connect($ACS_CONFIG["db_server"],$ACS_CONFIG["db_user"],$ACS_CONFIG["db_password"]);
              @mysql_select_db($ACS_CONFIG["db_name"],$ACS_con);

   $query = "SELECT review_id, review_date, user_id, rating, text, parent_id FROM review WHERE document_number = $doc_num AND parent_id = $parent_id ORDER BY rating DESC, review_date $ACS_ord";
   $ACS_res = @mysql_query($query);
   $ACS_cnt = @mysql_num_rows($ACS_res);
   if($ACS_cnt > 0)
   {
      //print out each comment and recursively print its children
      while($ACS_row = @mysql_fetch_array($ACS_res)){
         $ACS_con = @mysql_connect($ACS_CONFIG["db_server"],$ACS_CONFIG["db_user"],$ACS_CONFIG["db_password"]);
         @mysql_select_db($ACS_CONFIG["db_name"],$ACS_con);
         $query = "select username from users where user_id = " . $ACS_row["user_id"];
         $uresult = mysql_query($query) or die(mysql_error());
         $username = mysql_fetch_assoc($uresult);
         ?><div id = "Comment<?php echo $ACS_row['review_id']; ?>" style="margin-left:<?php echo $recurse_depth * 20; ?>px;" class="ACS_Comment"><img src="<?php echo $ACS_path; ?>img/speech-bubble.gif" alt="speech-bubble.gif" title="Speech bubble" />
          <i align="right" style="margin-top:-20px">
          <?php for ($i=0;$i<$ACS_row["rating"];$i++){
                ?><img src="<?php echo $ACS_path; ?>star.png" align="right" style="margin-right:10px"/><?php
          }?></i>

          <div class="ACS_Comment_Meta"><span class="ACS_Comment_Name">
          <a href = "profile.php?id=<?php echo $ACS_row["user_id"];?>">
          <?php echo $username["username"]; ?></a></span>
          <span class="grey">wrote on <?php echo $ACS_row["review_date"]; ?></span></div><div class="ACS_Comment_Body"><?php echo $ACS_row["text"]; ?>
          <div align="right"><a href="<?php echo $ACS_page; ?>?id=<?php echo $doc_num; ?>&replyto=<?php echo $ACS_row['review_id']; ?>#Comment<?php echo $ACS_row['review_id']; ?>">reply</a></div></div>
          </div> 
          <?php
             //check if replying to specific comment and print comment field if so
             if(isset($_GET['replyto']))
             {
                 $replyto = $_GET['replyto'];
             }
             else
             {
                $replyto = 0;
             }
             if($ACS_row["review_id"] == $replyto)
             {?>
                    <form action="<?php echo $ACS_page; ?>?id=<?php echo $doc_num; ?>&parent=<?php echo $replyto; ?>#ACS_Comments_Container" method="post" onsubmit="return ACS_submitComment();">

        <div style="margin-left:<?php echo $recurse_depth * 20; ?>px;">
      <div class="ACS_Comment_FormTitle">
        <table width="150px">
            <tr><td>Your rating:</td><td><select name="rate">
            <?php for ($i = 1; $i <= 5; $i++) { echo "<option value=\"$i\">$i</option>"; } ?>
              </select></td></tr>
        </table>

        <?php echo $ACS_CONFIG["your_message"]; ?> <span class="ACS_lightGrey">(required, minimum <?php echo $ACS_CONFIG["min_length_message"]; ?>, maximum <?php echo $ACS_CONFIG["max_length_message"]; ?> characters)</span></div>
      <div><textarea name="ACS_newCommentMessage" id="ACS_newCommentMessage" rows="10" cols="40" class="ACS_Comment_Form" style="background-image:url(<?php echo $ACS_path; ?>img/bg-input.gif);" onfocus="<?php if($ACS_CONFIG["textcounter_enabled"]){ ?>ACS_textCounter(this,'ACS_progressbar1',<?php echo $ACS_CONFIG["max_length_message"]; ?>);<?php } ?>ACS_changeClass(this,'ACS_Comment_Form ACS_Comment_FormFocus');" onblur="ACS_changeClass(this,'ACS_Comment_Form');"<?php if($ACS_CONFIG["textcounter_enabled"]){ ?> onkeydown="ACS_textCounter(this,'ACS_progressbar1',<?php echo $ACS_CONFIG["max_length_message"]; ?>);" onkeyup="ACS_textCounter(this,'ACS_progressbar1',<?php echo $ACS_CONFIG["max_length_message"]; ?>);"<?php } ?>></textarea></div>
      <?php if($ACS_CONFIG["textcounter_enabled"]){ ?><div class="ACS_progressContainer"><div id="ACS_progressbar1" class="ACS_progress">&nbsp;</div></div><?php } ?>
      <?php if($ACS_CONFIG["anti_spam_enabled"]){ ?>
      <div class="ACS_Comment_FormTitle"><?php echo $ACS_CONFIG["insert_letters"]; ?> <?php echo $ACS_code; ?> <span class="ACS_lightGrey">(required, case-sensitive)</span></div>
      <div><input type="text" name="ACS_newCommentAntiSpamCode" id="ACS_newCommentAntiSpamCode" value="" class="ACS_Comment_Form" style="background-image:url(<?php echo $ACS_path; ?>img/bg-input.gif);" onfocus="ACS_changeClass(this,'ACS_Comment_Form ACS_Comment_FormFocus');" onblur="ACS_changeClass(this,'ACS_Comment_Form');" /><input type="hidden" name="ACS_newCommentAntiSpamCodeVerification" id="ACS_newCommentAntiSpamCodeVerification" value="<?php echo $ACS_code; ?>" /></div>
      <?php } ?>
      <?php if($ACS_CONFIG["slider_enabled"]){ ?>
      <div class="ACS_Comment_FormTitle"><?php echo $ACS_CONFIG["drag_slider"]; ?> <span class="ACS_lightGrey">(required)</span></div>
      <div id="ACS_slider" class="ACS_slider" style="background-image:url(<?php echo $ACS_path; ?>img/bg-input.gif);"><div class="ACS_knob" id="ACS_sliderKnob"><img src="<?php echo $ACS_path; ?>img/arrow-right_white.gif" alt="arrow-right_white.gif" title="<?php echo $ACS_CONFIG["drag_slider"]; ?>" /><input type="hidden" name="ACS_newCommentSlider" id="ACS_newCommentSlider" value="0" /></div></div>
      <?php }else{ ?>
      <div>&nbsp;</div>
      <?php } ?>
      <div>
        <button type="submit" name="ACS_newCommentSubmit" style="background-image:url(<?php echo $ACS_path; ?>img/bg-input.gif);" id="ACS_newCommentSubmit" value="<?php echo $ACS_CONFIG["submit"]; ?>"><img src="<?php echo $ACS_path; ?>img/submit-form.gif" alt="submit-form.gif" title="<?php echo $ACS_CONFIG["submit"]; ?>" /> <?php echo $ACS_CONFIG["submit"]; ?></button>
        <input type="hidden" name="ACS_newCommentAntiSpamCodeEnabled" id="ACS_newCommentAntiSpamCodeEnabled" value="<?php echo $ACS_CONFIG["anti_spam_enabled"] ? "1" : "0"; ?>" />
        <input type="hidden" name="ACS_newCommentSliderEnabled" id="ACS_newCommentSliderEnabled" value="<?php echo $ACS_CONFIG["slider_enabled"] ? "1" : "0"; ?>" />
        <input type="hidden" name="ACS_newCommentTextCounterEnabled" id="ACS_newCommentTextCounterEnabled" value="<?php echo $ACS_CONFIG["textcounter_enabled"] ? "1" : "0"; ?>" />


        <input type="hidden" name="ACS_newCommentMessageMinLength" id="ACS_newCommentMessageMinLength" value="<?php echo $ACS_CONFIG["min_length_message"]; ?>" />
        <input type="hidden" name="ACS_newCommentMessageMaxLength" id="ACS_newCommentMessageMaxLength" value="<?php echo $ACS_CONFIG["max_length_message"]; ?>" />
        <input type="hidden" name="ACS_path" id="ACS_path" value="<?php echo $ACS_path; ?>" />
      </div>
      </div> 
    </form>
         <?php
             }
          ?>
          <?php
          //recursive to print children
          print_children_comments($ACS_path, $ACS_CONFIG, $doc_num, $ACS_row["review_id"], $recurse_depth+1);
       }
   }
   @mysql_close($ACS_con);
   $ACS_con = null;
}

?>
