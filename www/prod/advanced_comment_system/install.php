<?php
/* ########################## INFO ##########################

                   Advanced commentary system
                          Version 1.0

         Free script from WB - Webdesign for beginners.
          Visit http://plohni.com/wb for more scripts.
               Feel free to modify this script.
                              lgp

/* ########################## INFO ########################## */
/* ###################### INSTALLATION ###################### */

   // a) Adjust the configuration variables in config.php to
   //    your needs
   //
   // b) Copy the following line of code to the beginning of
   //    the PHP files you want to show comments (each page
   //    will have its own comments). Note that it must be
   //    inside a PHP tag. Adjust the variable $ACS_path. It
   //    must contain the relative (!) path to the folder
   //    which contains the advanced comment system and it
   //    must end with a slash.
   //
   //    Example 1 (Windows):
   //      Your PHP file:  C:\apache\htdocs\php\files\my.php
   //      Comment folder: C:\apache\htdocs\comments\
   //      --> $ACS_path = "../../comments/";
   //
   //    Example 2 (Unix):
   //      Your PHP file:  /var/www/html/php/files/my.php
   //      Comment folder: /var/www/html/comments/
   //      --> $ACS_path = "../../comments/";.
   //
   //    //HERE IS THE LINE OF CODE TO COPY:
   //    $ACS_path = "advanced_comment_system/";
   //
   // c) Copy the following three HTML elements (1x <link>,
   //    2x <script>) to the head (between <head> and </head>)
   //    of your PHP files. Remove the three underscores!
   //
   //    <link type="text/css" rel="stylesheet"
   //          href="<?php echo $ACS_path; ?_>css/style.css" />
   //    <script src="<?php echo $ACS_path; ?_>js/common.js"
   //            type="text/javascript"></script>
   //    <script src="<?php echo $ACS_path; ?_>js/mootools.js"
   //            type="text/javascript"></script>
   //
   // d) Add the following code to the onload-event of the
   //    body in your PHP files.
   //
   //    ACS_init();
   //
   //    If the body doesn't contain an onload-event yet, you
   //    may add the following line:
   //
   //    onload="ACS_init();"
   //
   // e) Copy the following line of code to your PHP file
   //    where you want the comments to appear. Note that it
   //    must be inside a PHP tag.
   //
   //    include($ACS_path."index.php");
   //
   // f) Copy the files (your PHP files and the files from
   //    advanced comment system) to your server.
   //
   // g) Call install.php in your webbrowser.
   //
   // 
   // For more information and a detailed installation manual
   // visit http://www.plohni.com/wb.
   //

/* ###################### INSTALLATION ###################### */
/* ############# SCRIPT (EDIT AT YOUR OWN RISK) ############# */

  //load configuration
  require("config.php");
  
  //connect to database
  @mysql_connect($ACS_CONFIG["db_server"],$ACS_CONFIG["db_user"],$ACS_CONFIG["db_password"]) or die("Database server connection failed. Check variables \$ACS_CONFIG[\"db_server\"], \$ACS_CONFIG[\"db_user\"] and \$ACS_CONFIG[\"db_password\"] in config.php");
  @mysql_select_db($ACS_CONFIG["db_name"]) or die("Selecting database failed. Check variable \$ACS_CONFIG[\"db_name\"] in config.php");
  
  //create table for comments
  @mysql_query("CREATE TABLE {$ACS_CONFIG["db_table"]} (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,date_inserted INT UNSIGNED NOT NULL,page VARCHAR(255) NOT NULL,username VARCHAR(255) NOT NULL,message TEXT NOT NULL)");

  //print result
  if(@mysql_error()){
    ?><p>Could not create table <?php echo $ACS_CONFIG["db_table"]; ?>: <?php echo @mysql_error(); ?></p><?php
  }else{
    ?><p>Table <?php echo $ACS_CONFIG["db_table"]; ?> created successfully. To avoid unexpected trouble please remove this file. <a href="admin.php">Click here to continue.</a></p><?php
  }

  //close database connection
  @mysql_close();
?>