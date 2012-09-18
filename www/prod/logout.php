<?php

  // Inialize session
session_start();

// Delete certain session
unset($_SESSION['username']);
// Delete all session variables
session_destroy();

// Delete Cookie
setcookie("username", "", time()-3600);

// Jump to login page
header('Location: index.php');

?>
<html>
</html>