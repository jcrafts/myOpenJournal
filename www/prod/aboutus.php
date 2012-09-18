<?php include('includes/cookiecheck.inc');

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
		<?php 
			include('navigation.php');
		?>
	</div>

	<div id="body_content">
	<div id="content">
		<h2 align="center">About MyOpenJournal</h2>
		<p><br></p>

		<span style="font-size: small "><strong>
		<p>myOpenJournal started as an idea to update a process that has been the same for centuries. Dr. Max I. Fomitchev-Zamilov and Dr. Lee Giles thought it would be best if knowledge from scientific papers was released to the general public for free. Beyond allowing free access and free distribution of information, it was envisioned that this collection of information should be peer reviewed on a massive scale.
		</p>
		<p>
From this basis, the idea for myOpenJournal was born. Now, our team of ten students in CMPSC 483W are spending a semester developing the site. In the end, we plan to have a working version of myOpenJournal, where anyone can create an online presence to submit, review, and learn at no cost.
		</p>
		</strong>

		
	</div>
	</div>
	
	<div id="footer">
		<a class="lastchild" href="aboutus.php">About Us</a>
		<a class="lastchild" href="contact.php">Contact</a>
		<a class="lastchild" href="privacy.php">Privacy Policy</a>
		<a class="lastchild" href="terms-of-use.php">Terms of Use</a>
		<a class="lastchild" href="faq.php">FAQ</a>
		<a class="lastchild" href="investors.php">Investors</a>
                <br>
		<p><br><br>Copyright of the MyOpenJournal team in CMPSC 483W Fall 2011</p>
	</div>

</div>
	
</body>

</html>
