<?php require_once "../inc/header.inc.php"; ?>

<?php
if( !adminConnect() ){ //Si l'admin N'EST PAS connecté, on le redirige vers la page connexion

  header('location:../connexion.php'); //redirection vers la page de conenxion
  exit();
}

//--------------TABLEAU AFFICHAGE DES SALLES-------------------//
$r = execute_requete("SELECT * FROM salle");
$content .= '<div class="table-responsive">';
$content .= '<table class="table table-dark">';
  $content .= '<thead>';
    $content .= '<tr>';
      for ($i=0; $i < $r->columnCount() ; $i++) {
        $colonne = $r->getColumnMeta( $i );
        if ($colonne['name'] == 'description') {
          $content .= '<th scope="col" colspan="2">' . $colonne['name'] . '</th>';
        }
        else{
          $content .= '<th scope="col">' . $colonne['name'] . '</th>';
        }
    }
    $content .= '<th scope="col">Action</th>';
      while ($ligne = $r->fetch(PDO::FETCH_ASSOC) ) {
        $content .= '<tr>';
        // debug($ligne);
          foreach ($ligne as $key => $value) {
            if ($key == 'photo') {
              $content .= "<td><img src='$value' alt='photo de la salle' style='width:100px;'></td>";
            }
            elseif ($key == 'description'){
              $content .= "<td colspan='2'>$value</td>";
            }
            else{
              $content .= "<td>$value</td>";
            }
          }
          $content .= '<td>
                        <p><a href="'.URL.'">
                          <i class="fas fa-search"></i>
                        </a></p>
                        <p><a href="?action=edit&id_salle='. $ligne['id_salle'] .'">
                          <i class="far fa-edit"></i>
                        </a></p>
                        <p><a href="?action=delete&id_salle='. $ligne['id_salle'] .'" onclick="return( confirm(\'Es tu certain ?\') )" >
                        <i class="far fa-trash-alt"></i>
                        </a></p>
                      </td>';

        $content .= '</tr>';
      }
    $content .= '</tr>';
  $content .= '</thead>';
$content .= '</table>';
$content .= '</div>';
//--------------TABLEAU AFFICHAGE DES SALLES------------------//

//--------------INSERTION-----------------------------------//

  //gestion de la photo :
  if( !empty( $_FILES['photo']['name'] ) ){ // si $_FILES['photo']['name'] n'est pas vide c'est que l'on a uploader un fichier grâce à l'input type="file"

    //Ici, je renomme ma photo :
    $nom_photo = $_POST['titre'] . '_'  . $_FILES['photo']['name'];

    //chemin pour accéder à la photo en BDD :
    $photo_bdd = URL . "photo/$nom_photo";

      // http://localhost/COURS_PHP_IFOCOP/boutique/photo/$nom_photo'

    //Ou est-ce que l'on veut stocker la photo : chemin pour accéder au dossier photo sur le server
    $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . "/cours_ifocop_php/Switch/photo/$nom_photo";

      // C:/xampp/htdocs/COURS_PHP_IFOCOP/boutique/photo/$nom_photo
      // $_SERVER['DOCUMENT_ROOT'] <=> C:/xampp/htdocs

    copy( $_FILES['photo']['tmp_name'], $photo_dossier );
    // $_FILES['photo']['tmp_name'] : correspond à l'endroit où se trouve temporairement votre fichier que vous souhaitez uploader.

    //copy( arg1 , arg2 );
      //arg1 : chemin du fichier source
      //arg2 : chemin de destination
  }
if (!empty( $_POST )) {
  // debug($_POST);
  foreach ($_POST as $key => $value) { //ici, on passe toutes les informations postées du formulaire dans les fonctions htmlentities() et addslashes() :

    $_POST[$key] = htmlentities( addslashes( $value ) );
  }
//--------------MODIF-----------------------------------//


if (isset($_GET['action']) && $_GET['action'] == 'edit') {
  // debug($_GET);
  execute_requete("UPDATE salle SET
    titre = '$_POST[titre]',
    description = '$_POST[description]',
    photo = '$photo_bdd',
    capacite = '$_POST[capacite]',
    categorie = '$_POST[categorie]',
    pays = '$_POST[pays]',
    ville = '$_POST[ville]',
    adresse = '$_POST[adresse]',
    cp = '$_POST[cp]'
    WHERE id_salle = '$_GET[id_salle]'");
  header('location:gestion_salle.php');
    exit();
  }
  else{
    //Insertion nouvelle salle :
      execute_requete(" INSERT INTO salle(titre, description, photo, pays, ville, adresse, cp, capacite, categorie)
            VALUES(
                  '$_POST[titre]','$_POST[description]','$photo_bdd',
                  '$_POST[pays]','$_POST[ville]','$_POST[adresse]','$_POST[cp]',
                  '$_POST[capacite]','$_POST[categorie]'
            ) ");
      header('location:gestion_salle.php');
      exit();

  }
}
//--------------MODIF-----------------------------------//

//--------------INSERTION-----------------------------------//

//--------------SUPPRESSION-----------------------------------//

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
  // debug($_GET);

  //SUPPRESSION DE LA PHOTO :
  //récupération des informations du produit que je veux supprimer:
  $r = execute_requete(" SELECT * FROM salle WHERE id_salle = '$_GET[id_salle]' ");

  //Application de la méthode fetch() pour pouvoir récupérer et exploiter les données :
  $salle_a_supprimer = $r->fetch( PDO::FETCH_ASSOC );
    //debug( $article_a_supprimer );
    // debug( $_SERVER );

  //chemin de la photo à supprimer :
  $chemin_photo_a_supprimer = str_replace( "http://localhost:8888", $_SERVER['DOCUMENT_ROOT'], $salle_a_supprimer['photo'] );

    // debug( $chemin_photo_a_supprimer );

  if( !empty( $chemin_photo_a_supprimer ) && file_exists( $chemin_photo_a_supprimer ) ){
  //Si le chemin de la photo a supprimer n'est pas vide ET que le fichier existe

    unlink( $chemin_photo_a_supprimer );
    //unlink() : permet de supprimer un fichier
  }

  execute_requete('DELETE FROM salle WHERE id_salle ='. $_GET['id_salle'] . ' ');
  header('location:gestion_salle.php');
    exit();
}

//--------------SUPPRESSION-----------------------------------//

if( isset( $_GET['id_salle'] ) ){
  //je récupère les infos de l'article à modifier :
  $r = execute_requete(" SELECT * FROM salle WHERE id_salle = '$_GET[id_salle]' ");

  $salle_actuel = $r->fetch(PDO::FETCH_ASSOC);
  }
  $titre = ( isset($salle_actuel['titre']) ) ? $salle_actuel['titre'] : '';
  $description = ( isset($salle_actuel['description']) ) ? $salle_actuel['description'] : '';
  $capacite = ( isset($salle_actuel['capacite']) ) ? $salle_actuel['capacite'] : '';
  $categorie_r = ( isset($salle_actuel['categorie']) &&  $salle_actuel['categorie'] == 'reunion') ? 'selected' : '';
  $categorie_b = ( isset($salle_actuel['categorie']) &&  $salle_actuel['categorie'] == 'bureau') ? 'selected' : '';
  $categorie_f = ( isset($salle_actuel['categorie']) &&  $salle_actuel['categorie'] == 'formation') ? 'selected' : '';
  $pays = ( isset($salle_actuel['pays']) ) ? $salle_actuel['pays'] : '';
  $ville = ( isset($salle_actuel['ville']) ) ? $salle_actuel['ville'] : '';
  $adresse = ( isset($salle_actuel['adresse']) ) ? $salle_actuel['adresse'] : '';
  $cp = ( isset($salle_actuel['cp']) ) ? $salle_actuel['cp'] : '';
  ?>


<div class="container">
  <h1>Gestion des salles</h1>
  <?= $content ?>
  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group">
          <label for="titre">Titre</label>
          <input type="text" class="form-control" name="titre" id="titre">
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control" name="description" id="description" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label for="photo">Photo</label>
          <input type="file" name="photo" id="photo" class="form-control-file">
        </div>
          <?php
            if (isset($salle_actuel)) {
              echo '<i>Vous pouvez uploader une nouvelle photo</i> <br>';
              echo "<img src='$salle_actuel[photo]'width='80'>";
              echo "<input type='hidden' name='photo_actuelle' value='$salle_actuel[photo]'>";              }
          ?>
        <div class="form-group">
          <label for="capacite">Capacité</label>
          <input type="number" class="form-control" name="capacite" id="capacite">
        </div>
        <div class="form-group">
          <label for="categorie">Catégorie</label>
          <select class="form-control" name="categorie" id="categorie" name="categorie">
            <option value="reunion">Réunion</option>
            <option value="bureau">Bureau</option>
            <option value="formation">Formation</option>
          </select>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group">
          <label for="pays">Pays</label>
          <input type="text" class="form-control" name="pays" id="pays">
        </div>
        <div class="form-group">
          <label for="ville">Ville</label>
          <input type="text" class="form-control" name="ville" id="ville">
        </div>
        <div class="form-group">
          <label for="adresse">Adresse</label>
          <textarea class="form-control" name="adresse" id="adresse" rows="2"></textarea>
        </div>
        <div class="form-group">
          <label for="cp">Code Postal</label>
          <input id="cp" name="cp" type="text" pattern="[0-9]*" class="form-control">
        </div>
        <input type="submit" class="btn btn-primary" name="validation" value="Enregistrer"></input>

      </div>

    </div>
  </form>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "../inc/footer.inc.php"; ?>
