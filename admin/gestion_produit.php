<?php require_once "../inc/header.inc.php"; ?>

<?php
  if( !adminConnect() ){ //Si l'admin N'EST PAS connecté, on le redirige vers la page connexion

    header('location:../connexion.php'); //redirection vers la page de conenxion
    exit();
  }
//AFFICHAGE produit --------------------------------------------
$r = execute_requete("SELECT id_produit, date_arrivee, date_depart, salle.id_salle, salle.titre, salle.photo,prix, etat FROM produit, salle WHERE salle.id_salle = produit.id_salle");
// $produits = $r->fetch(PDO::FETCH_ASSOC);
// debug($produits);
$content .= '<div class="table-responsive">';
$content .= '<table class="table table-dark">';
  $content .= '<thead>';
    $content .= '<tr>';
      for ($i=0; $i < $r->columnCount() ; $i++) {
        $colonne = $r->getColumnMeta( $i );
        if ($colonne['name'] == 'photo') {

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
            }
            elseif ($key == 'id_salle'){
              $content .= "<td><p>$ligne[id_salle] - Salle $ligne[titre]</p><img src='$ligne[photo]' alt='photo de la salle' style='width:100px;'></td>";

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

//AFFICHAGE produit --------------------------------------------

//INSERTION produit --------------------------------------------
  if (!empty( $_POST )) {
    debug($_POST);
    execute_requete("INSERT INTO produit(id_salle, date_arrivee, date_depart, prix) VALUES(
        '$_POST[salle_select]',
        '$_POST[date_arrivee]',
        '$_POST[date_depart]',
        '$_POST[tarif]'
      ) ");
    header('location:gestion_produit.php');
      exit();
  }
//INSERTION produit --------------------------------------------
?>
<div class="container">
  <h1>Gestion des produits</h1>
  <?= $content  ?>
  <form method="post">
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group">
          <label for="date_arrivee">Date d'arrivée</label>
          <input type="date" class="form-control" name="date_arrivee" id="date_arrivee">
        </div>
        <div class="form-group">
          <label for="date_depart">Date de départ</label>
          <input type="date" class="form-control" name="date_depart" id="date_depart">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group">
          <label for="salle_select">Salle</label>
          <select class="form-control" name="salle_select" id="salle_select">
            <?php
              $r = execute_requete("SELECT id_salle, titre, adresse, cp, ville, capacite, categorie FROM salle");
              while ( $salle = $r->fetch(PDO::FETCH_ASSOC) ) {
                debug($salle);
                echo '<option value="' . intval($salle['id_salle']) . '">';
                foreach ($salle as $key => $value) {
                  if ($key == 'categorie' ) {
                    echo "$value";
                  }
                  elseif( $key == 'adresse' || $key == 'cp'){
                    echo "$value, ";
                  }
                  elseif ($key == 'capacite') {
                    echo "$value personnes - ";
                  }
                  else {
                    echo "$value - ";
                  }
                }
                echo '</option>';
              }
             ?>
          </select>
        </div>
        <div class="form-group">
          <label for="tarif">Tarif</label>
          <input type="number" placeholder="prix en euros" name="tarif" id="tarif" class="form-control">
        </div>
        <input type="submit" class="btn-primary" value="enregistrer">
      </div>
    </div>
  </form>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "../inc/footer.inc.php"; ?>
