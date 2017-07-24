<?php include "base_donnees.php"; 

$query = 'SELECT `tp_membre`.`id_membre`, `tp_fiche_personne`.`nom` AS nom, `tp_fiche_personne`.`prenom` AS prenom , `tp_abonnement`.`nom` AS abo FROM `tp_membre` LEFT JOIN `tp_fiche_personne` ON `tp_membre`.`id_fiche_perso`=`tp_fiche_personne`.`id_perso` LEFT JOIN `tp_abonnement` ON `tp_abonnement`.`id_abo`=`tp_membre`.`id_abo`';

$emplace = array();

if(!empty($_GET))
{
  if(isset($_GET['nom']) && $_GET['nom'] != "")
  {
    $nom = $_GET['nom'];
    $query .= ' WHERE `tp_fiche_personne`.`nom` LIKE ? ';
    $emplace[] = "%$nom%";
  }
  if(isset($_GET['prenom']) && $_GET['prenom'] != "")
  {
    $prenom = $_GET['prenom'];
    $query .= ' WHERE `tp_fiche_personne`.`prenom` LIKE ? ';
    $emplace[] = "%$prenom%";
  }
}

$query .= ' ORDER BY nom';


$resultats = $bdd->prepare($query);
$resultats->execute($emplace);
$pagination = $resultats->fetchAll();

$nbMembre = count($pagination); 


$limit = 10;

if(isset($_GET['page'])) 
{
  $Pageact = $_GET['page'];
}
else
{
  $Pageact = 1; 
}

if(isset($_GET['limit']))
{
  $limit = $_GET['limit'];
  $query .= ' LIMIT '.(($Pageact - 1)*$limit).", $limit";
}
else
{
  $query .= ' LIMIT '.(($Pageact - 1)*$limit).", $limit";
}



$resultats = $bdd->prepare($query);
$resultats->execute($emplace);
$categories = $resultats->fetchAll();

$nbPages = ceil($nbMembre/$limit);


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



    
      <h2>Recherche de membre</h2>

      <form method="GET" name="searchMember" action="membre.php" >
        <label for="nom">Nom : </label>
        <input type="text" class="form-control" id="nom" name="nom" />

        <label for="prenom">Prénom : </label>
        <input type="text" id="prenom" class="form-control" name="prenom" />

        <label for="limit">Affichage par page :</label>
        <input type="number" id="limit" name="limit"  class="form-control" value="15" min="0" />

        <input type="submit" id="valid" name="Validation" value="Go !" />

      </form>
    

   
      <header>Membres</header>
      <table id="indexMembres" class="table table-bordered">
        <tr class="warning">
          <th>Nom</th>
          <th>Prénom</th>
          <th>Abonnement</th>
          <th>Infos</th>
        </tr>
        <?php
        if(!empty($categories))
        {
          foreach($categories as $elem)
          {
            ?>
            <tr class="success">
              <td><?php echo $elem['nom']; ?></td>
              <td><?php echo $elem['prenom']; ?></td>
              <td><?php echo $elem['abo']; ?></td>
              <td><a href="ficheMembre.php<?php echo "?"."id=".$elem['id_membre']?>">En Savoir +</a></td>
            </tr>
            <?php
          }
        }
        else
        {
          ?>
          <tr><td><?php echo "Aucun resultat "; ?></td></tr>
          <?php
        }
        ?>
      </table>
      <?php
      if($Pageact < $nbPages)
      {
        ?>
        <a href='<?php echo "?"; if(isset($nom)) echo "nom=" . $nom; if(isset($prenom)) echo "&amp;prenom=" . $prenom; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($Pageact+1); ?>' class="next" title="lien vers la page suivante">Suivant &rarr;</a>
        <?php
      }
      if($Pageact > 1)
      {
        ?>
        <a href='<?php echo "?"; if(isset($nom)) echo "nom=" . $nom; if(isset($prenom)) echo "&amp;prenom=" . $prenom; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($Pageact-1); ?>' class="previous" title="lien vers la page précédente">&larr; Précédent</a>
        <?php
      }
      ?>

 

    
  </div>