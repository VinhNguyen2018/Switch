<?php require_once "inc/header.inc.php"; ?>

<?php
// debug($_GET);
if( isset($_GET['id_salle']) ){ //Si il existe 'id_produit' dans mon URL c'est que j'ai bien sélectionné un produit

  $r = execute_requete("SELECT titre, description, photo, adresse, cp, ville, categorie FROM salle WHERE id_salle = '$_GET[id_salle]'");
}
else{ //Sinon, redirection vers l'accueil

  header('location:index.php');
  exit(); //quitte le script courant
}

// debug($_SESSION);
$salle = $r->fetch(PDO::FETCH_ASSOC);
$pseudo = $_SESSION['membre']['pseudo'];
$id_membre = $_SESSION['membre']['id_membre'];

// debug($salle);

if (!empty( $_POST )) {
  debug($_POST);
  foreach ($_POST as $key => $value) { //ici, on passe toutes les informations postées du formulaire dans les fonctions htmlentities() et addslashes() :

    $_POST[$key] = htmlentities( addslashes( $value ) );
  }
  execute_requete("INSERT INTO avis(id_membre, id_salle, commentaire, note, date_enregistrement)
            VALUES(
                  '$id_membre','$_GET[id_salle]',
                  '$_POST[commentaire]','$_POST[note]',NOW()
            ) ");
      header('location:avis.php?id_salle=' . $_GET['id_salle']);
      exit();
}
?>

<div class="container">
  <div class="card mt-4">
      <img class="card-img-top img-fluid" src="<?= $salle['photo']  ?>" alt="photo salle $salle['titre']">
      <div class="card-body">
        <h3 class="card-title"><?= $salle['categorie']== 'bureau' ? 'Bureau ' . $salle['titre'] : 'Salle ' . $salle['titre']  ?></h3>
        <p class="card-text"><?= $salle['description']  ?></p>
        <span class="text-warning"><?= affichage_note_etoile($_GET['id_salle']); ?></span>
        <?= calcul_note_avis($_GET['id_salle']); ?> / 5 étoiles
      </div>
    </div>
  <div class="card card-outline-secondary my-4">
    <div class="card-header">
      Vos avis
    </div>
    <?php
    $r = execute_requete("SELECT avis.id_membre ,commentaire, pseudo, note, avis.date_enregistrement, avis.id_salle FROM membre, avis, salle WHERE avis.id_membre = membre.id_membre AND avis.id_salle = salle.id_salle AND salle.id_salle = '$_GET[id_salle]'");

    while ( $avis = $r->fetch(PDO::FETCH_ASSOC) ) {
      $date_commentaire = date('d/m/Y' , strtotime($avis['date_enregistrement']));
      $heure_commentaire = date('H:i' , strtotime($avis['date_enregistrement']));
      echo '<div class="card-body">';
        echo '<p><span class="text-warning">';
        if ($avis['note'] < 5) {
          $remaining = 5 - $avis['note'];
          for ($i=0; $i < $avis['note'] ; $i++) {
            echo '<i class="fas fa-star"></i>';
          }
          for ($i=0; $i < $remaining ; $i++) {
            echo '<i class="far fa-star"></i>';
          }
        }
        else {
          for ($i=0; $i < $avis['note'] ; $i++) {
            echo '<i class="fas fa-star"></i>';
          }
        }
        echo '</span></p>';
        echo '<p>';
          echo $avis['commentaire'];
        echo '</p>';
        echo '<small class="text-muted">Posté par ';
          echo $pseudo . ' le ' . $date_commentaire. ' à ' . $heure_commentaire;
        echo ' </small>';
      echo '</div>';
      echo '<hr>';
    }






     ?>
     <br>
    <div class="card-header">
      Laissez un commentaire
    </div>
    <br>
      <form method="post">
        <div class="form-group formulaire-avis pl-3">
        <label for="note">Votre note</label>
          <select class="form-control" name="note" id="note">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
        </div>
        <div class="form-group formulaire-avis pl-3">
          <label for="commentaire">Votre message</label>
          <textarea class="form-control" name="commentaire" id="commentaire" cols="30" rows="10"></textarea>
        </div>
        <input type="submit" class="btn btn-success ml-3 mb-3" value="Poster votre commentaire">
      </form>
    </div>

  </div>

</div>



<?php require_once "inc/footer.inc.php"; ?>
