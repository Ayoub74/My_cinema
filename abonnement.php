<?php include "base_donnees.php"; ?>

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

 <?php
$query = 'SELECT `id_abo`, `nom`, `resum`, `prix`, `duree_abo` AS duree FROM `tp_abonnement` WHERE `id_abo` ORDER BY `prix`';

$abos = $bdd->query($query);
$abos = $abos->fetchAll();

$query = 'SELECT `nom`, `pourcentage_reduc` AS pourcentage FROM `tp_reduction` ORDER BY pourcentage';

$reduc = $bdd->query($query);
$reduc = $reduc->fetchAll();

?>
 <div id="main">
		

		<section id="abo">
			<h2>Abonnements</h2>
			<table class="table table-bordered">
				<tr class="warning">
					<th>Nom</th>
					<th>Détails</th>
					<th>Prix</th>
					<th>Durée</th>
				</tr>
				<?php
				foreach ($abos as $elem) 
				{
					?>
					<tr class="success">
						<td><?php echo $elem['nom']; ?></td>
						<td><?php echo $elem['resum']; ?></td>
						<td><?php echo $elem['prix']; ?></td>
						<td><?php echo $elem['duree']; ?></td>
					</tr>
					<?php
				}
				?>
			</table>

		</section>

		<section id="reduc">
			<h2>Réductions</h2>
			<table class="table table-bordered">
				<tr class="warning">
					<th>Nom</th>
					<th>Pourcentage</th>
				</tr>
				<?php
				foreach ($reduc as $elem) 
				{
					?>
					<tr class="success">
						<td><?php echo $elem['nom']; ?></td>
					
					
					<td><?php echo $elem['pourcentage']; ?></td>
				</tr>
				<?php
			}
			?>
		</table>

	</section>




</div>

</body>
</html>