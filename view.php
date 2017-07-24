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

$reponse = $bdd->query('SELECT `nom`, `id_genre` FROM `tp_genre` ORDER BY `nom`');
$listGenre = $reponse->fetchAll();


$reponse = $bdd->query('SELECT `nom`, `id_distrib` FROM `tp_distrib` ORDER BY `nom`');
$listDistrib = $reponse->fetchAll();


$query = 'SELECT `id_film`, `titre`, `annee_prod` AS annee, `tp_genre`.`nom` AS genre FROM `tp_film` LEFT JOIN `tp_genre` ON `tp_film`.`id_genre` = `tp_genre`.`id_genre` LEFT JOIN `tp_distrib` ON `tp_distrib`.`id_distrib` = `tp_film`.`id_distrib` WHERE 1';

$emplace= array();

if(!empty($_GET))
{
  if(isset($_GET['genre']) && $_GET['genre'] != "Default")
  {
    $genre = $_GET['genre'];
    $query .= " AND `tp_genre`.`id_genre` = ? ";
    $emplace[] = $genre;
  }
  if(isset($_GET['distrib']) && $_GET['distrib'] != "Default")
  {
    $distrib = $_GET['distrib'];
    $query .= ' AND `tp_distrib`.`id_distrib` = ? ';
    $emplace[] = $distrib;
  }
  if(isset($_GET['titre']) && $_GET['titre'] != "")
  {
    $titre = $_GET['titre'];
    $query .= ' AND `titre` LIKE ? ';
    $emplace[] = "%$titre%";
  }
}

$query .= ' ORDER BY `titre`';


$resultats = $bdd->prepare($query);
$resultats->execute($emplace);
$pagination = $resultats->fetchAll();

$nbFilm = count($pagination); 

$limit = 10;

if(isset($_GET['page'])) 
{
  $actPage = $_GET['page'];
}
else
{
  $actPage = 1;
}

if(isset($_GET['limit']))
{
  $limit = $_GET['limit'];
  $query .= ' LIMIT '.(($actPage - 1)*$limit).", $limit";
}
else
{
  $query .= ' LIMIT '.(($actPage - 1)*$limit).", $limit";
}


$resultats = $bdd->prepare($query);
$resultats->execute($emplace);
$avecFiltres = $resultats->fetchAll();

$nbPages = ceil($nbFilm/$limit);



?>

  <div id="main">
  

    <section class="bloc_left">
      <header><h2>RECHERCHE DE FILMS</h2></header>

   <form method="GET" class="navbar-form navbar-left" name="searchFilm" action="index.php" >
    <label for="titre">Titre : </label>
      <input class="form-control" type="text" id="titre" name="titre" />

      <label for="genre">Genre : </label>
       <select class="form-control" id="genre" name="genre">

             <option>Default</option>
        

           <?php
             foreach ($listGenre as $elem)
              {
              ?>

               <option <?php if(isset($_GET['genre']) && $elem['id_genre'] == $_GET['genre']) ?> value="<?= $elem['id_genre']; ?>"><?php echo $elem['nom']; ?></option>
               <?php
          }
          ?>
        </select>

            <label for="distrib">Distributeur : </label>
               <select class="form-control" id="distrib" name="distrib">

                <option>Default</option>
                  <?php 
    
          foreach ($listDistrib as $elem)
          {
            ?>
                  <option <?php if(isset($_GET['distrib']) && $elem['id_distrib'] == $_GET['distrib']) ?> value="<?= $elem['id_distrib']; ?>"><?php echo $elem['nom']; ?></option>
                   <?php
           }
          ?>
        </select>

        <label for="limit">Affichage par page :</label>
        <input class="form-control" type="number" id="limit" name="limit" value="10" min="0" />

        <input type="submit" class="btn btn-default"  id="valid" name="Validation"  />




      </form>
    </section>

    
     
      <table id="indexFilm" class="table table-bordered">
        <tr class="warning">
          <th>Titre</th>
          <th>Annee</th>
          <th>Genre</th>
           </tr>
        <?php

        if(!empty($avecFiltres))
        {
          foreach($avecFiltres as $elem)
          {
            ?>
            <tr class="success">
              <td><?php echo $elem['titre']; ?></td>
              <td>
                <?php 
                if($elem['annee'] == 0)
                {
                  echo "inconnue";
                }
                else
                {
                  echo $elem['annee']; ?>
                  <?php
                }
                ?>
              </td>
              <td>
                <?php 
                if($elem['genre'] == NULL)
                {
                  echo "inconnu";
                }
                else
                {
                  echo $elem['genre']; ?>
                  <?php
                }
                ?>

            <?php
          }
        }
        else
        {
          ?>
          <tr><td><?php echo "Aucun film trouvée"; ?></td></tr>
          <?php

        }
        ?>
      </table>

      <?php
      if($actPage < $nbPages)
      {
        ?>
          <a href='<?php echo "?"; if(isset($genre)) echo "genre=" . $genre; if(isset($distrib)) echo "&amp;distrib=" . $distrib; if(isset($titre)) echo "&amp;titre=" .$titre; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($actPage+1); ?>' class="next">Suivant &rarr;</a>
        <?php
      }
      if($actPage > 1)
      {
        ?>
        <a href='<?php echo "?"; if(isset($genre)) echo "genre=" . $genre; if(isset($distrib)) echo "&amp;distrib=" . $distrib; if(isset($titre)) echo "&amp;titre=" .$titre; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($actPage-1); ?>' class="previous">&larr; Précédent</a>
        <?php
      }
      ?>



  
  </div>

</body>
</html>