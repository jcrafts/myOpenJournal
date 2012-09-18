<div id="ACS_Comments_Container">
<?php
  //load configuration
  require($ACS_path."config.php");
  
  //load functions
  require($ACS_path."functions.php");

  //current page
  $ACS_page = $_SERVER["PHP_SELF"];
  $doc_num = $_GET['id'];
///
  
  //insert new comment
  if(isset($_REQUEST["ACS_newCommentMessage"])){
    $ACS_newCommentMessage = $_REQUEST["ACS_newCommentMessage"];
    $ACS_newCommentAntiSpamCode = $_REQUEST["ACS_newCommentAntiSpamCode"];
    $ACS_newCommentAntiSpamCodeVerification = $_REQUEST["ACS_newCommentAntiSpamCodeVerification"];
    $ACS_newCommentSlider = $_REQUEST["ACS_newCommentSlider"];
    $ACS_commentMessageError = "<div class='ACS_error_message'>{$ACS_CONFIG["error_message"]}</div>";
    $ACS_commentMessageSuccess = "<div class='ACS_success_message'>{$ACS_CONFIG["success_message"]}</div>";
    $parent_id = $_GET['parent'];

    //check inputs
    if(strlen($ACS_newCommentMessage)<$ACS_CONFIG["min_length_message"] || strlen($ACS_newCommentMessage)>$ACS_CONFIG["max_length_message"] || ($ACS_CONFIG["anti_spam_enabled"] && $ACS_newCommentAntiSpamCode!=$ACS_newCommentAntiSpamCodeVerification) || ($ACS_CONFIG["slider_enabled"] && $ACS_newCommentSlider!=1)){
      echo $ACS_commentMessageError;
    }else{
      //prepare values for insert
      $ACS_newCommentMessage = ACS_prepareInsert($ACS_newCommentMessage);
      $ACS_con = @mysql_connect($ACS_CONFIG["db_server"],$ACS_CONFIG["db_user"],$ACS_CONFIG["db_password"]);
                 @mysql_select_db($ACS_CONFIG["db_name"],$ACS_con);
      $rate = isset ($_POST['rate']) ? $_POST['rate'] : 1;
      $uname = $_COOKIE['username'];
      $query1 = "SELECT user_id FROM users where username = '$uname'";
      $result = mysql_query($query1, $ACS_con);
      $uid = mysql_fetch_assoc($result);
      $user_id = $uid['user_id'];


      //insert comment
      //$ACS_query = "INSERT INTO {$ACS_CONFIG["db_table"]} (date_inserted,page,username,message) VALUES (".time().",'$ACS_page','$ACS_newCommentName','$ACS_newCommentMessage');";
      $query = "INSERT INTO review (rating, text, review_date, parent_id, document_number, user_id) VALUES ($rate, '$ACS_newCommentMessage', '" . date('Y-m-d H:i:s') . "', $parent_id, $doc_num, $user_id)";

      @mysql_query($query,$ACS_con);

      if(!@mysql_error($ACS_con) && @mysql_insert_id($ACS_con)){
        echo $ACS_commentMessageSuccess;

        //send mail notification
        if($ACS_CONFIG["email_enabled"]){
          @mail($ACS_CONFIG["notification_email"],$ACS_CONFIG["notification_subject"],"Page: $ACS_page\nName: $ACS_newCommentName\n\n$ACS_newCommentMessage","To: {$ACS_CONFIG["notification_email"]}\n");
        }
      }else{
        echo $ACS_commentMessageError;
        echo $query;
      }
      
      @mysql_close($ACS_con);
      $ACS_con = null;
    }
  }
 
  //print comments
  $ACS_ord = $ACS_CONFIG["comments_order"] == "top" ? "DESC" : "ASC";
  $ACS_con = @mysql_connect($ACS_CONFIG["db_server"],$ACS_CONFIG["db_user"],$ACS_CONFIG["db_password"]);
             @mysql_select_db($ACS_CONFIG["db_name"],$ACS_con);
  $query = "SELECT review_date, user_id, rating, text, parent_id FROM review WHERE document_number = $doc_num AND parent_id = $parent_id ORDER BY review_date $ACS_ord";
  //$ACS_res = @mysql_query("SELECT date_inserted,username,message FROM {$ACS_CONFIG["db_table"]} WHERE page='$ACS_page' ORDER BY date_inserted $ACS_ord;",$ACS_con);
  $ACS_res = @mysql_query($query);
  $ACS_cnt = @mysql_num_rows($ACS_res);
?>
  <div id="ACS_Comment_LeaveAComment">
    <h1><?php echo $ACS_CONFIG["leave_comment"]; ?></h1>
    <a href="<?php echo $ACS_page; ?>?id=<?php echo $doc_num; ?>&replyto=0#ACS_Comment_LeaveAComment"><?php echo $ACS_CONFIG["write_comment"]; ?></a>
    <?php $ACS_code = ACS_generateAntiSpamCode($ACS_CONFIG["anti_spam_code_length"]); 
    //if replyto is set to reply to document, print the form
    if(isset($_GET['replyto']))
    {
        $replyto = $_GET['replyto'];
    }
    if($replyto == 0)
    {?>

    <form action="<?php echo $ACS_page; ?>?id=<?php echo $doc_num; ?>&parent=0#ACS_Comments_Container" method="post" onsubmit="return ACS_submitComment();"> 
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
    </form>
    <?php } ?>
  </div>
  <h1><?php echo $ACS_CONFIG["title"]; ?></h1>
  <?php if($ACS_CONFIG["allow_hide"]){ ?>
    <div id="ACS_Comments_Hide" style="display:none;"><a href="javascript:void(0);" onclick="ACS_hideComments();"><?php echo $ACS_CONFIG["hide_comments"]; ?></a> </div>
    <div id="ACS_Comments_Show" style="display:none;"><?php echo $ACS_CONFIG["comments_hidden"]; ?> <a href="javascript:void(0);" onclick="ACS_showComments();"><?php echo $ACS_CONFIG["show_comments"]; ?></a> </div>
  <?php } ?>

    <div id="ACS_Comments" style="display:none;">
  <?php
    print_children_comments($ACS_path, $ACS_CONFIG,$doc_num,0, 0);
 ?>
  </div>

</div>
