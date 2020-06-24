<?php require_once "../inc/header.inc.php"; ?>

<?php
if( !adminConnect() ){ //Si l'admin N'EST PAS connecté, on le redirige vers la page connexion

  header('location: '.URL.'connexion.php'); //redirection vers la page de conenxion
  exit();
}

// SUPPRESSION AVIS

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
  execute_requete("DELETE FROM avis WHERE id_avis = '$_GET[id_avis]'");
  header('location: '.URL.'gestion_avis.php');
    exit();

}

// AFFICHAGE DES AVIS TABLEAU

$r = execute_requete("SELECT id_avis, avis.id_membre , avis.id_salle, commentaire, note, avis.date_enregistrement FROM avis, membre, salle WHERE avis.id_membre = membre.id_membre AND avis.id_salle = salle.id_salle");


$content .= '<div class="table-responsive">';
$content .= '<table class="table table-dark">';
  $content .= '<thead>';
    $content .= '<tr>';
      for ($i=0; $i < $r->columnCount() ; $i++) {
        $colonne = $r->getColumnMeta( $i );
        // debug($colonne);
        $content .= '<th scope="col">' . $colonne['name'] . '</th>';
      }
    $content .= '<th scope="col">Action</th>';
      while ($ligne = $r->fetch(PDO::FETCH_ASSOC) ) {
        $content .= '<tr>';
        // debug($ligne);
          foreach ($ligne as $key => $value) {
            if ($key == 'id_membre') {
              $test = execute_requete("SELECT email FROM membre WHERE id_membre = $value");
              $email = $test->fetch(PDO::FETCH_ASSOC);
              $content .= '<td>' . $value . ' - ' . $email['email'] . '</td>';
            }
            elseif ($key == 'id_salle'){
              $test = execute_requete("SELECT titre FROM salle WHERE id_salle = $value");
              $titre = $test->fetch(PDO::FETCH_ASSOC);
              $content .= '<td>' . $value . ' - ' . $titre['titre']. '</td>';
            }
            elseif ($key == 'date_enregistrement'){
              $content .= '<td>'.date('d/m/Y H:i' ,strtotime($value)).'</td>';
            }
            elseif ($key == 'note') {
              $content .= '<td>';
              if ($value < 5) {
                $remaining = 5 - $value;
                for ($i=0; $i < $value ; $i++) {
                  $content .= '<i class="fas fa-star"></i>';
                }
                for ($i=0; $i < $remaining ; $i++) {
                  $content .= '<i class="far fa-star"></i>';
                }
              }
              else {
                for ($i=0; $i < $value ; $i++) {
                  $content .= '<i class="fas fa-star"></i>';
                }
              }
              $content .= '</td>';
            }
            elseif ($key != 'email' || $key != 'titre'){
              $content .= "<td>$value</td>";
            }
          }
          $content .= '<td>
                        <p><a href="'.URL.'avis.php?id_salle='.$ligne['id_salle'] .'">
                          <i class="fas fa-search"></i>
                        </a></p>
                        <p><a href="?action=edit&id_avis='. $ligne['id_avis'] .'">
                          <i class="far fa-edit"></i>
                        </a></p>
                        <p><a href="?action=delete&id_avis='. $ligne['id_avis'] .'" onclick="return( confirm(\'Es tu certain ?\') )" >
                        <i class="far fa-trash-alt"></i>
                        </a></p>
                      </td>';

        $content .= '</tr>';
      }
    $content .= '</tr>';
  $content .= '</thead>';
$content .= '</table>';
$content .= '</div>';

?>

<div class="container">
  <h1>Gestion des avis</h1>
  <hr>
  <?= $content  ?>
</div>


<?php require_once "../inc/footer.inc.php"; ?>
