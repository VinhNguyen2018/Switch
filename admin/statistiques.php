<?php require_once "../inc/header.inc.php"; ?>
<?php

//-----------------------------------------------------------

if( !adminConnect() ){ //Si l'admin N'EST PAS connecté, on le redirige vers la page connexion

  header('location:../connexion.php'); //redirection vers la page de conenxion
}

$content .= '<h5>Top 5 des salles les mieux notées</h5>';
$r = execute_requete("SELECT salle.titre, AVG(note) AS 'moyenne' FROM avis,salle WHERE salle.id_salle = avis.id_salle GROUP BY salle.titre ORDER BY moyenne DESC LIMIT 5");

$content .= '<ol class="list-group" style="list-style: decimal inside;">';
  while ( $salles = $r->fetch(PDO::FETCH_ASSOC) ) {
  // debug($salles);
      $content .= '<li class="list-group-item" style="display: list-item;">'.$salles['titre'].' - '.number_format($salles['moyenne'],2).'/5</li>';
  }
$content .= '</ol>';

$r = execute_requete("SELECT COUNT(id_commande) AS commandes, salle.titre FROM commande, salle, produit WHERE salle.id_salle = produit.id_salle AND produit.id_produit = commande.id_produit GROUP BY salle.titre ORDER BY commandes DESC LIMIT 5");
$content .= '<h5>Top 5 des salles les plus commandées</h5>';
  $content .= '<ol class="list-group" style="list-style: decimal inside;">';
    while ( $salles = $r->fetch(PDO::FETCH_ASSOC) ) {
  // debug($salles);
      $content .= '<li class="list-group-item d-flex justify-content-between align-items-center" style="display: list-item;">'.$salles['titre']. '<span class="badge badge-primary badge-pill">'.intval($salles['commandes']).'</span></li>';
  }
  $content .= '</ol>';

$content .= '<h5>Top 5 des membres qui achètent le plus (en terme quantité)</h5>';

$r = execute_requete("SELECT COUNT(id_commande) AS commande, membre.email FROM commande, membre WHERE membre.id_membre = commande.id_membre GROUP BY membre.email ORDER BY commande DESC LIMIT 5");

while ( $commande = $r->fetch(PDO::FETCH_ASSOC) ) {
  $content .= '<li class="list-group-item d-flex justify-content-between align-items-center" style="display: list-item;">'.$commande['email']. '<span class="badge badge-primary badge-pill">'.intval($commande['commande']).'</span></li>';
}


$content .= '<h5>Top 5 des membres qui achètent le plus cher(en terme de prix)</h5>';

$r = execute_requete("SELECT SUM(produit.prix) as prix_total, membre.email FROM membre,produit, commande WHERE membre.id_membre = commande.id_membre AND produit.id_produit = commande.id_produit GROUP BY membre.email ORDER BY prix_total DESC LIMIT 5 ");

while ( $total = $r->fetch(PDO::FETCH_ASSOC) ) {
  $content .= '<li class="list-group-item d-flex justify-content-between align-items-center" style="display: list-item;">'.$total['email']. '<span class="badge badge-primary badge-pill">'.intval($total['prix_total']).'€</span></li>';
}

//-----------------------------------------------------------

?>
<div class="container">
  <div class="stat-box">
  <h1>Statistiques</h1>
    <?= $content  ?>

  </div>
</div>
<?php require_once "../inc/footer.inc.php"; ?>
