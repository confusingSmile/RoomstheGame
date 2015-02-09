<html>
	<head>
		<link rel="stylesheet" type="text/css" href="csslayout.css" />
	</head>
	<body>
	
		<?php
			session_start();
			if(isset($_SESSION['user'])){
				//avoiding the "undefined index" error by making sure $output is set.
				if(!(isset($output))){
					$output = "";
				}
			
				echo "<div id=\"commandIn\">
						  <div id=\"headsUpDisplay\">
						  </div>
							  <form action=\"index.php\" method=\"post\">
								  <center><textarea cols=\"100\" rows=\"20\">".$output."</textarea><br><br>
								  <input type=\"text\" id=\"commandTextField\" name=\"input\" value=\"\">
								  <input type=\"submit\" value=\"OK\"></center><br>
							  </form>
					  </div>";
			
			
			} else {
					
				echo "<form action=\"login.php\" method=\"post\">
						  <input type=\"text\" name=\"username\" value=\"\">
						  <input type=\"text\" name=\"password\" value=\"\">
						  <input type=\"submit\" value=\"Log in\">
					  </form>";
				
			}
		
			
		?>
		
		
	</body>
</html>
