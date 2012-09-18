<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Advanced comment system - Administration</title>
    <link type="text/css" rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <div id="ACS_Admin_Container">
      <h1>Advanced comment system - Administration</h1>
<?php
  //load configuration
  require($ACS_path."config.php");

  //password
  $pw = $_GET["pw"];

  //simple login
  if($pw == $ACS_CONFIG["admin_password"]){
    //connect to database
    @mysql_connect($ACS_CONFIG["db_server"],$ACS_CONFIG["db_user"],$ACS_CONFIG["db_password"]) or die("Database server connection failed. Check variables \$ACS_CONFIG[\"db_server\"], \$ACS_CONFIG[\"db_user\"] and \$ACS_CONFIG[\"db_password\"] in config.php");
    @mysql_select_db($ACS_CONFIG["db_name"]) or die("Selecting database failed. Check variable \$ACS_CONFIG[\"db_name\"] in config.php");
    
    $del = $_GET["del"];
    $res = $_GET["res"];
    $page = $_GET["page"];
    
    //print selectbox to choose page
    ?><form method="get" action="admin.php"><div><input type="hidden" name="pw" value="<?php echo $pw; ?>" /><select name="page"><option value=""<?php if($page==""){ ?> selected="selected"<?php } ?>>Select page to show comments</option><?php
    $result = @mysql_query("SELECT DISTINCT(page) as page FROM {$ACS_CONFIG["db_table"]} ORDER BY page ASC;");
    
    while($row=@mysql_fetch_array($result)){
      ?><option value="<?php echo $row["page"]; ?>"<?php if($page==$row["page"]){ ?> selected="selected"<?php } ?>><?php echo $row["page"]; ?></option><?php
    }
    
    ?></select> <input type="submit" name="submit" value="go" /></div></form><?php

    //delete comment
    if(isset($del)){
      @mysql_query("DELETE FROM {$ACS_CONFIG["db_table"]} WHERE id=$del;");
      
      if(@mysql_affected_rows()==1){
        ?><p>Comment has been deleted.</p><?php
      }else{
        ?><p>Could not delete comment.</p><?php
      }
    }
    
    //print comments
    $ord = $ACS_CONFIG["comments_order"] == "top" ? "DESC" : "ASC";
    $result = @mysql_query("SELECT id,date_inserted,username,message FROM {$ACS_CONFIG["db_table"]} WHERE page='$page' ORDER BY date_inserted $ord;");
    
    if(@mysql_num_rows($result)){
      ?><table align="center" border="0" cellpadding="5" cellspacing="1">
        <tr>
	  <th>Id</th>
	  <th>Date</th>
	  <th>Name</th>
	  <th>Message</th>
	  <th>&nbsp;</th>
        </tr><?php

      $colored = true;
      
      while($row=@mysql_fetch_array($result)){
        ?><tr<?php if($colored){ ?> class="ACS_Admin_colored"<?php } ?>>
          <td width="50"><?php echo $row["id"]; ?></td>
          <td width="100"><?php echo date($ACS_CONFIG["date_format"],$row["date_inserted"]); ?><br /><?php echo date($ACS_CONFIG["time_format"],$row["date_inserted"]); ?></td>
          <td width="150"><div style="width:150px";><?php echo $row["username"]; ?></div></td>
          <td width="500"><div style="width:500px";><?php echo $row["message"]; ?></div></td>
          <td><a href="admin.php?pw=<?php echo $pw; ?>&amp;page=<?php echo $page; ?>&amp;del=<?php echo $row["id"]; ?>">delete</a></td>
        </tr><?php $colored = !$colored;
      }

      ?></table><?php
    }else{
      if($page!=""){
        ?><p>No comments found for the selected page.<?php
      }else{
        ?><p>Please select a page.<?php
      }
    }
    
    //show logout link
    ?><p><a href="admin.php">[logout]</a></p><?php

    //close database connection
    @mysql_close();
  }else{
    //print login form
    ?><form action="admin.php" method="get">Password: <input name="pw" type="password"> <input type="submit" value="Login"></form><?php
  }
?>
    </div>
  </body>
</html>