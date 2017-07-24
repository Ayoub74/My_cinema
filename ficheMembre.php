<?php

 include "base_donnees.php"; 


$membre = $_GET['id'];


$reponse = $bdd->query('SELECT `id_film`, `titre` FROM `tp_film` ORDER BY `titre`');
$listFilm = $reponse->fetchAll();





if(isset($_GET['addFilmabb']) && $_GET['addFilm'] != "Default")
{
	if(isset($_GET['addDate'])) 
	{
		$addFilm = $_GET['addFilm'];
		$addDate = $_GET['addDate'];

		$AjoutHistoriqueFilm = "INSERT INTO `tp_historique_membre` (`id_membre`, `id_film`, `date`) VALUES ('$membre', '$addFilm', '$addDate')";
		
		$bdd->query($AjoutHistoriqueFilm);

	}
}




$req = "SELECT `tp_film`.`id_film` AS idFilm, `tp_film`.`titre` AS titre, `tp_historique_membre`.`date` AS jour FROM `tp_membre` LEFT JOIN `tp_historique_membre` ON `tp_historique_membre`.`id_membre`=`tp_membre`.`id_membre` LEFT JOIN `tp_film` ON `tp_film`.`id_film`=`tp_historique_membre`.`id_film` WHERE `tp_membre`.`id_membre` = $membre ORDER BY jour DESC ";


$resultats = $bdd->query($req);
$historique = $resultats->fetchAll();

$nbfh = count($historique); 

$limit = 8;

if(isset($_GET['page']))
{
	$Pageact = $_GET['page'];
}
else
{
	$Pageact = 1; 
}

if(isset($_GET['limitHistoriquev']))
{
	$limit = $_GET['limit'];
	$req .= ' LIMIT '.(($Pageact- 1)*$limit).", $limit";
}
else
{
	$req .= ' LIMIT '.(($Pageact - 1)*$limit).", $limit";
}

$reponse = $bdd->query($req);
$historique = $reponse->fetchAll();


$reponse = $bdd->query('SELECT `nom`, `id_abo` FROM `tp_abonnement` ORDER BY `nom`');
$listAbo = $reponse->fetchAll();




$nbPages = ceil($nbfh/$limit);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  

  <title>My Cinema</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  
  <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
  

  <link rel="stylesheet" href="style.css">

</head>

<body>

  <nav class="navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Mon Cinema</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
         
        
        <li><a href="index.php">Recherche</a></li>
        <li><a href="abonnement.php">Abonnement</a></li>
        <li><a href="membre.php">Membre</a></li>
        
      </ul>
  

     </div>
     
   </div>

 </nav>

	<div id="main">
		
		

		
		<section id="add_film_historique">
		<header><h2>Ajouter un film</h2></header>

			<form method="GET" name="addFilm"  action="ficheMembre.php">
				<input type="hidden" name="id"  value="<?php echo $membre; ?>"/>

			
				<select class="form-control" id="addFilm" name="addFilm">
					<option>Default</option>
					<?php
					foreach ($listFilm as $elem) 
					{
						?>
						<option value="<?php echo $elem['id_film']; ?>"><?php echo $elem['titre']; ?></option>
						<?php
					}
					?>
				</select>

				<input type="date" id="addDate" name="addDate" />

				<input type="submit" id="addFilmabb" name="addFilmabb" value="Ajouter" />
			</form>			
		</section>

		

		<section id="films_vus">
			<header><h2>Historique flim</h2></header>

			<form method="GET" name="limitHistorique" id="limitHistorique" action="ficheMembre.php">
				<input type="hidden" name="id" value="<?php echo $membre; ?>"/>

				<label for="limit">Affichage :</label>
				<input type="number" id="limit" name="limit" value="10" min="0" />

				<input type="submit" id="limitHistoriquev" name="limitHistoriquev" value="Ok" />
			</form>

			<table id="historique" class="table table-bordered">
				<tr class="warning">
					<th>Film</th>
					<th>Date</th>
					
				</tr>
				<?php
				foreach ($historique as $elem) 
				{
					?>
					<tr class="success"> 
						<td><?php echo $elem['titre']; ?></td>
						<td><?php echo $elem['jour']; ?></td>
						
					</tr>
					<?php
				}
				?>

			</table>

			<?php
			if($Pageact < $nbPages)
			{
				?>
				<a href='<?php echo "?"; echo "&amp;id=" . $membre; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($Pageact+1); ?>' class="next" title="lien vers la page suivante">Suivant &rarr;</a>
				<?php
			}
			if($Pageact> 1)
			{
				?>
				<a href='<?php echo "?"; echo "&amp;id=" . $membre; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($Pageact-1); ?>' class="previous" title="lien vers la page précédente">&larr; Précédent</a>
				<?php
			}
			?>

		</section>

		
	</div>

</body>
</html>