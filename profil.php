<?php require_once "inc/header.inc.php"; ?>
<?php

if( !userConnect() ){ //SI l'internaute N'EST PAS conencté

  //redirection vers la page de connexion
  header('location: '.URL.'connexion.php');

  exit(); //exit() : termine le script courant
}
//-----------------------------------------------------------

if( adminConnect() ){ //si on est connecté et que l'on est admin, on affiche un titre 'admnistrateur'

  $content .= '<h2 style="color:red;">ADMINISTRATEUR</h2>';
}

//-----------------------------------------------------------

// suppression commande

if (isset($_GET['action']) && $_GET['action'] == 'delete') {
  execute_requete("DELETE FROM commande WHERE id_commande = '$_GET[id_commande]'");
  header('location: '.URL.'profil.php');
  exit();
}

//debug( $_SESSION );

$content .= '<h3>Bonjour ' . $_SESSION['membre']['pseudo'] . '</h3>';

$content .= '<p>Voici vos informations :</p>';

$content .= '<p>Votre nom : '. $_SESSION['membre']['nom'] .'</p>';
$content .= '<p>Votre prénom : '. $_SESSION['membre']['prenom'] .'</p>';
$content .= '<p>Votre email : '. $_SESSION['membre']['email'] .'</p>';

$content .= '<hr>';
$content .= '<h3>Historique de vos commandes</h3>';
$id_membre = $_SESSION['membre']['id_membre'];
$r = execute_requete("SELECT id_commande, produit.id_produit, produit.prix, date_enregistrement FROM commande, produit WHERE commande.id_membre = $id_membre AND commande.id_produit = produit.id_produit ");

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
          if ($key == 'id_produit'){
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
                        <p><a href="'.URL.'fiche_produit.php?id_produit='.$ligne['id_produit'].'">
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
  <h1>PROFIL</h1>

  <?= $content; //affichage ?>

</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "inc/footer.inc.php"; ?>
