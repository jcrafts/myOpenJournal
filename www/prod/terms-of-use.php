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
		<h2 align="center">Terms of Use</h2>
		<p><br></p>
                
                <center><h3> Welcome to our website.</h3></center>
                <p> 

                    <span style="font-size: small "><strong>

                        If you continue to browse and use this website, you are agreeing to comply with and be bound by the 
			following terms and conditions of use, which together with our privacy policy govern [business name]'s relationship with you 
			in relation to this website. If you disagree with any part of these terms and conditions, please do not use our website.

			<br>
			<br>

		    	The term 'myOpenJournal' or 'us' or 'we' refers to the owners of the website whose registered office is [address]. 
			Our company registration number is [company registration number and place of registration]. The term 'you' refers to the user or viewer of our website.

			<br>
			<br>

		    	<p>The use of this website is subject to the following terms of use:</p>
		    </strong>

		    <ul>
			    <li>The content of the pages of this website is for your general information and use only. It is subject to change without notice.
			    </li>

			    <li>This website uses cookies to monitor browsing preferences. If you do allow cookies to be used, the following personal 
				information may be stored by us for use by third parties: [insert list of information].
			    </li>

			   <li> Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness 
				or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that 
				such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or 
				errors to the fullest extent permitted by law.
			   </li>

			    <li>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. 
				It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.
			    </li>

			    <li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, 
				layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.
			    </li>

			    <li>
				All trade marks reproduced in this website which are not the property of, or licensed to, the operator are acknowledged on the website.
			    </li>

			    <li>
				Unauthorised use of this website may give rise to a claim for damages and/or be a criminal offence.
			    </li>
			    <li>
				From time to time this website may also include links to other websites. These links are provided for your 
				convenience to provide further information. They do not signify that we endorse the website(s). 
				We have no responsibility for the content of the linked website(s).
			    </li>
			    <li>
				Your use of this website and any dispute arising out of such use of the website is subject to the laws of England, Northern Ireland, Scotland and Wales.
			    </li>
		   </ul>
	</p>


		
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
