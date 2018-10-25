<!DOCTYPE html>

<html>
	<body>
		<h1> TP1 </h1>
		<?php
			$x = 7; 
			$i = 0;
			$max_table = 10;
			
			echo ('la table de multiplication de ' . $x . ' est :');
			echo ('<br/>');
			echo ('<br/>');
			while($i <= $max_table){
				echo ( $x . ' x ' . $i . ' = ' . $x*$i . '<br/>');
				$i++;
			}
			
		?>
	</body>
</html>