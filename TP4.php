<!DOCTYPE html>

<html>
	<head>
		<style>
		#formulaire input{
			position: absolute;
			left: 150px;
		}
		form div{
			margin: 5px;
			display:block;
			height:20px;
		}
		table{
				width: 800px;
				text-align:center;
		}
		</style>
	</head>
	<body>
	<?php
		
		function connectBDD(){
			return mysqli_connect('localhost', 'root', 'root','Inscription');
		}
		function controle(){ // appelle les tests sur chaque type de données du formulaire
			return (testAge() && testNom() && testPrenom() && testDate()) ;
		}
		
		function testAge(){ // test la bonne forme de la donnée age
			return ($_POST['user_age']!='' && is_numeric($_POST['user_age']) && $_POST['user_age']> 0);
		}
		
		function testDate(){ // test la bonne forme de la donnée date
			return (preg_match( '^[0-9]{4}\-[0-9]{2}\-[0-9]{2}^' , $_POST['user_date']) 
					&& strtotime($_POST['user_date']));
		}
		
		function testNom(){ // test la bonne forme de la donnée nom
			return ($_POST['user_nom']!='' && is_string($_POST['user_nom']));
		}
		
		function testPrenom(){ // test la bonne forme de la donnée prenom
			return $_POST['user_prenom']!='' && is_string($_POST['user_prenom']);
		}
			
		function demandeInsert(){
			
			if (!controle())// controle toute les données saisies par l utilisateur
				return "un ou plusieurs champs incorrect";
			else{
				$cnx = connectBDD(); // connexion à la base Inscription
				
				$sql = "SELECT * FROM inscrits WHERE nom='".$_POST['user_nom']."'"; // construction de la requete de recherche de doublons
				//echo($sql); // affichage de la requete avant envoie
				
				$req = mysqli_query($cnx,$sql); // execute la requete sur la base
				//var_dump($req); // affiche le résultat de la requete
				
				$row=mysqli_fetch_row($req); // parcours le premier résultat
				//var_dump($row);
				
				if (isset($req) && $req && $row!=null) // vérifie qu'aucun résultat n'a été trouvé 
					return "Nom déjà existant"; // message si doublon trouvé
				else{
					$datetime = strtotime($_POST['user_date']); // transforme la chaine date en timestamp
					$date = date('Y-m-d',$datetime); // transforme le timestamp en format DATE pour la BDD
					
					//construction de la requete d'insertion 
					$sql = "INSERT INTO inscrits  
							VALUES (null,'".$_POST['user_nom']."','"
									. $_POST['user_prenom']."','"
									. $_POST['user_age']."','"
									. $date."')";
					//echo($sql);
					if (mysqli_query($cnx,$sql)) // execution de la requete d'insertion
						return $erreur = afficheInscrits();
					else
						return "Erreur insertion MySQL";
				}
			}
		}
		function afficheInscrits(){

				$cnx =connectBDD();// connexion à la base Inscription
				
				$sql = "SELECT * FROM inscrits ";// construction de la requete de recherche de doublons
				// echo($sql); // affichage de la requete avant envoie
				
				$req = mysqli_query($cnx,$sql); // execute la requete sur la base
				//var_dump($req); // affiche le résultat de la requete
				
				if(!isset($req) && !$req): // vérifie que des résultat existent
					return 'erreur de requete affichage';
				else:?>
					<table>
						<tbody>
							<tr> 
								<th>ID</th>
								<th>Nom</th>
								<th>Prenom</th>
								<th>Age</th>
								<th>Date de Naissance</th>
					<?php while($row=mysqli_fetch_row($req)): // boucle sur chaque ligne de résultat de la requete sql?> 
							<tr>
						<?php foreach($row as $key => $element): // parcours chaque élément d'une ligne résultat de la requete?>
								<td>
							<?php echo($element); //affiche un élément de la ligne résultat?>
								</td>
							<?php endforeach; ?>
								<td>
									<!-- mise en place du bouton de demande de modification de la ligne en cours-->
									<form action="" method="post">
										<input type="hidden" value ="modifie" name="action"/>
										<input type="hidden" value ="<?php echo($row[0]); ?>" name="user_id"/>
										<input type="submit" value="Modifier"/>
									</form>
								</td>
								<td>
									<!-- mise en place du bouton de demande de suppression de la ligne en cours-->
									<form action="" method="post">
										<input type="hidden" value ="supprime" name="action"/>
										<input type="hidden" value ="<?php echo($row[0]) ?>" name="user_id"/>
										<input type="submit" value="Supprimer"/>
									</form>
								</td>
							</tr>
						<?php 
						//var_dump($row);
					endwhile; ?>
						</tbody>
					</table>
					<?php
					return 0;
				endif;
		}
		
		function demandeSupprime(){
			$cnx =connectBDD();// connexion à la base Inscription
			
			$id=$_POST['user_id'];
			
			$sql = "SELECT * FROM inscrits WHERE id='".$id."'"; // construction de la requete de recherche de doublons
			// echo($sql); // affichage de la requete avant envoie
			
			$req = mysqli_query($cnx,$sql); // execute la requete sur la base
			// var_dump($req); // affiche le résultat de la requete

			if ( isset($req) && !empty($req)){ // vérifie qu'aucun résultat n'a été trouvé 
				//construction de la requete de suppression 
				$sql = "DELETE FROM inscrits  
						WHERE id='".$id."'";
				// echo($sql);
				$req=mysqli_query($cnx,$sql);
				
				$erreur = afficheInscrits(); // demande d'afficher à nouveau le tableau résultat
				if ($req) // execution de la requete d'insertion
					return $erreur+=0;
				else {
					return $erreur+=" erreur requete de suppression";
				}
			}
			else{
				return "id de suppression non trouve";
			}
			//*/
		}
		
		function demandeModif(&$inscrit){
			$cnx = connectBDD(); // connexion à la base Inscription
			if(empty($_POST['user_id']))
				return 'id vide';
			else{
				$id=$_POST['user_id'];
			
				$sql = "SELECT * FROM inscrits WHERE id='".$id."'"; // construction de la requete pour récupérer la ligne à modifier
				// echo($sql); // affichage de la requete avant envoie
				
				$req=mysqli_query($cnx,$sql);
				
				if(!isset($req) || !$req) // vérifie que la requete a bien un résultat
					return "requete pour récupérer la ligne à modifier échoué";
				else{
					$row=mysqli_fetch_row($req);// récupère la ligne résultat
					
					foreach($row as $key => $value){ // parcours la ligne résultat pour récupérer les données 
						$inscrit[$key]=$value; // et les stocker dans un tableau 
					}
				}
			}
		}
		
		function demandeUpdate(){
			
			if (!controle())// controle toute les données saisies par l utilisateur
				return "un ou plusieurs champs incorrect";
			else{
				$cnx = connectBDD(); // connexion à la base Inscription
				
				//construction de la requete d'update 
				$sql = "UPDATE inscrits  
						SET nom='".$_POST['user_nom']
							."',prenom='".$_POST['user_prenom']
							."',age='".$_POST['user_age']
							."',date_de_naissance='".$_POST['user_date']."'
						WHERE id='".$_POST['user_id']."';";
				//echo($sql);
				if (mysqli_query($cnx,$sql)) // execution de la requete d'update
					return $erreur = afficheInscrits(); // demande d'afficher à nouveau le tableau résultat
				else
					return "Erreur update MySQL";
				//*/
			}
		}
		
		$display=0;
		$erreur='';
		$inscrit = array();
		$nom='';
		$prenom='';
		$age='';
		$date='';
		date_default_timezone_set('Europe/Paris'); 

		//var_dump($_POST);
		if(!empty($_POST)){	// controle si données post existe
			$action=$_POST['action'];
			switch ($action){ // vérifie l'action demander
				case 'affiche':
					$erreur.=afficheInscrits();
					break;
				case 'insert':
					$erreur=demandeInsert();
					break;
				case 'modifie':
					$erreur=demandeModif($inscrit);
					break;
				case 'supprime':
					$erreur=demandeSupprime();
					break;
				case'update':
					$erreur=demandeUpdate();
					break;
				default:
					$erreur='action non reconnu';
					break;
			}
		}
		else{
			$erreur=1;
		}
		
		//var_dump($erreur);
		if ($erreur): // vérifie si un message d'erreur existe  ?>
			<div>
				<p><?php if($erreur!=1){echo($erreur);} // et l'affiche ?></p>
			</div>
		<?php
			$display=1; // autorise l'affichage du formulaire
		endif;
				
		if(empty($_POST)){ // si première consulation de la page
			$action = 'insert'; // passe en mode insert 
			$display=1;	// autorise l'affichage du formulaire
		}
		
		if(!empty($inscrit)){ // si on a récupéré un inscrit à afficher pour le formulaire d'update
			$action='update';
			$display=1;
			$nom=$inscrit[1];
			$prenom=$inscrit[2];
			$age=$inscrit[3];
			$date=$inscrit[4];
		}
		
		if ($display): // si l'affichage du formulaire a bien été autorisé
		?>
			<!-- affichage du formulaire de base -->
			<form id="formulaire" action="" method="post">
				<div>
					<label for="name">Nom :</label>
					<input type="text" id="nom" value="<?php echo($nom); ?>" name="user_nom">
				</div>
				<div>
					<label for="mail">Prenom:</label>
					<input type="text" id="prenom" value="<?php echo($prenom); ?>" name="user_prenom">
				</div>
				<div>
					<label for="msg">Age:</label>
					<input type="number" id="age" value="<?php echo($age); ?>" name="user_age">
				</div>
				<div>
					<label for="msg">Date de naissance:</label>
					<input type="date" id="date" value="<?php echo($date); ?>" name="user_date">
				</div>
				<div>
					<input type="hidden" value ="<?php echo($action); ?>" name="action"/>
					<?php if(!empty($inscrit)): ?>
					<input type="hidden" value ="<?php echo($inscrit[0]); ?>" name="user_id"/>
					<?php endif; ?>
					<input type="submit" value="<?php echo ($action. ' donnees'); ?>"/>
				</div>
			</form>
		
		<!-- formulaire pour demander l'affichage de la table -->
			<form action="" method="post">
				<input type="hidden" value ="affiche" name="action"/>
				<input type="submit" value="Afficher tableau"/>
			</form>
			
		<?php else: ?>
			<!-- formulaire pour demander insertion  -->
			<form action="" method="post">
				<input type="submit" value="retour"/>
			</form>
		<?php endif; ?>
		
	</body>
</html>

