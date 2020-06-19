<?php require_once "../inc/header.inc.php"; ?>
<?php

//-----------------------------------------------------------

if( !adminConnect() ){ //Si l'admin N'EST PAS connecté, on le redirige vers la page connexion

  header('location:'.URL.'connexion.php'); //redirection vers la page de conenxion
}

//-----------------------------------------------------------

$r = execute_requete("SELECT id_commande, id_membre, produit.id_produit, produit.prix, date_enregistrement FROM commande, produit WHERE commande.id_produit = produit.id_produit ");

$content .= '<div class="table-responsive">';
  $content .= '<table class="table table-dark">';
    $content .= '<thead>';
      $content .= '<tr>';
        for ($i=0; $i < $r->columnCount() ; $i++) {
          $colonne = $r->getColumnMeta( $i );
          $content .= '<th scope="col">' . $colonne['name'] . '</th>';
        }
      $content .= '<th scope="col">Action</th>';
      while ( $ligne = $r->fetch(PDO::FETCH_ASSOC) ) {
        $content .= '<tr>';
        foreach ($ligne as $key => $value) {
          if ($key == 'id_membre') {
            $r2 = execute_requete("SELECT email FROM membre WHERE id_membre = $value");
            $email = $r2->fetch(PDO::FETCH_ASSOC);
            $content .= '<td>'.$value.' - '.$email['email'].'</td>';
          }
          elseif ($key == 'id_produit'){
            $r3 = execute_requete("SELECT salle.id_salle, salle.titre, date_depart, date_arrivee FROM produit, salle WHERE id_produit = $value AND salle.id_salle = produit.id_salle");
            $produit = $r3->fetch(PDO::FETCH_ASSOC);
            $content .= '<td>'
             . $produit['id_salle'] .
             ' - '
             . $produit['titre'] .
             '<br> <i>'
             . date('d-m-Y' ,strtotime($produit['date_arrivee'])) .
             ' au '
             . date('d-m-Y' ,strtotime($produit['date_depart'])) .
                        '</i></td>';
          }
          elseif ($key == 'date_enregistrement') {
            $date = date('d-m-Y H:i' ,strtotime($value));
            $content .= "<td>$date</td>";
          }
          else {
            $content .= "<td>$value</td>";
          }
        }
        $content .= '<td>
                        <p><a href="'.URL.'">
                          <i class="fas fa-search"></i>
                        </a></p>
                        <p><a href="?action=delete&id_commande='. $ligne['id_commande'] .'" onclick="return( confirm(\'Es tu certain ?\') )" >
                        <i class="far fa-trash-alt"></i>
                        </a></p>
                      </td>';

        $content .= '</tr>';
      }
    $content .= '</tr>';
  $content .= '</thead>';
$content .= '</table>';
$content .= '</div>';


//----------------------------------------------------------------------------------------------
?>
<div class="container">
  <h1>GESTION DES COMMANDES</h1>

  <?= $content; //affichage ?>

</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "../inc/footer.inc.php"; ?>
