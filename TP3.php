<!DOCTYPE html>

<html>
	<head>
		<style>
			table
			{
				border-collapse: collapse; /* Les bordures du tableau seront collées (plus joli) */
				border-style: double;
				margin: auto;
				margin-bottom: 50px;
			}
			tr
			{
				border: 1px solid black;
			}
			td
			{
				padding: 5px;
				padding-left: 20px;
				padding-right: 20px;
			}
		</style>
	</head>
	<body>
		<h1> TP 3 </h1>
		
		<?php
		$x = 1; 
		$i = 0;
		$max_table = 11;
		$tabMultipl = array();
		
		while( $x<11 ){	// boucle de remplissage du tableau contenant les tables de multiplication
			if($i==0){ 		
				$tabMultipl ['la table de multiplication de ' . $x . ' est :'] = '';
			}
			
			$tabMultipl [$x . ' x ' . $i] = $x*$i;
			$i++;		

			if ($i==$max_table){ 
				$i=0;		
				$x++;		
			}
		}
		$cpt=1;?>
		
		
		<?php foreach($tabMultipl as $multiplicateur => $resultat): 
			if ($cpt%12==1):?>
		<table>
			<tbody>
			<?php endif;?>
				<tr>
					<td>
					<?php echo($multiplicateur);?>
					</td> 
					<td>
					<?php echo($resultat)?>
					</td>
				</tr>
					<?php
			if($cpt%12==0):
			?>
			</tbody>
		</table>
				<?php
			endif;
			$cpt++;
		endforeach;?>
	</body>
</html>














