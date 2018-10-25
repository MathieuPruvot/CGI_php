<!DOCTYPE html>

<html>
	<body>
		<h1> TP2 </h1>
		<?php
		
			$x=1; 		// valeur de la table de multiplication en cours
			$i=0;		 // valeur du multiplicateur
			$max_table = 11;	 // variable de fin des tables de multiplication si jamais on veut des tables plus longues
			
			while( $x<11 ){
				
				if($i==0){ 		// si c'est la première ligne de la table 
					echo ('la table de multiplication de ' . $x . ' est :'); 	// ecrit le titre de la table 
					echo ('<br/><br/>');
				}
				
				echo ( $x . ' x ' . $i . ' = ' . $x*$i . '<br/>'); 		// ecrit la ligne de multiplication
				$i++; 		// passe au multiplicateur suivant
	
				if ($i==$max_table){ 		// si la dernière vient d'etre écrite
					$i=0;		// on réinitialise le multiplicateur
					echo ('<br/><br/> ----------------------------------------------------------------<br/>');
					$x++;		// on passe à la valeur suivante
					
				}
			}
		?>
	</body>
	<script>
	</script>
</html>