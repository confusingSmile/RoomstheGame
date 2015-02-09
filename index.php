<html>
	<head>
	</head>
	<body>
	
		<?php
			if(isset($_SESSION['user'])){
			
				echo "<form action=\"index.php\" method=\"post\">
						  <textarea>".$output."</textarea>
						  <input type=\"text\" name=\"input\" value=\"\">
						  <input type=\"submit\" value=\"OK\">
					  </form>";
			
			
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
