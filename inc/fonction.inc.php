<?php
//fonction debug() : permet d'effectuer un print_r() "amélioré" :
function debug( $arg ){

  echo '<div style="background:orange; padding: 5px; z-index:1000;">';

    $trace = debug_backtrace();
    //debug_backtrace() : fonction prédéfinie qui retourne un array contenant des infos
    echo 'Debug demandé dans le fichier : ' . $trace[0]['file'] . ' à la ligne ' . $trace[0]['line'] ;

    print '<pre>';
      print_r( $arg );
    print '</pre>';

  echo '</div>';
}

//-------------------------------------------------------------
//fonction execute_requete() :
function execute_requete( $req ){

  global $pdo;

  $pdostatement = $pdo->query( $req );

  return $pdostatement;
}

//-------------------------------------------------------------
//fonction userConnect() : si l'internaute est connecté
function userConnect(){

  //Si la session membre n'existe pas, cela signifie que l'on ne s'est pas connecté, on retourne 'false'
  if( !isset( $_SESSION['membre'] ) ){

    return false;
  }
  else{ //SINON, c'est qu'on s'est connecté, on retourne 'true'
    return true;
  }
}

//-------------------------------------------------------------
//fonction adminConnect() : si l'internaute est connecté ET est admin
function adminConnect(){

  if( userConnect() && $_SESSION['membre']['statut'] == 1 ){
  //si l'internaute est connecté ET qu'il est administrateur (donc qu'il a le statut = 1 en bdd)

    return true;
  }
  else{
    return false;
  }
}

// fonction calcul_note_avis
function calcul_note_avis($id_salle){
  $r = execute_requete("SELECT note FROM avis WHERE id_salle = '$id_salle'");
  $note_total = 0;
  $nombre_de_notes = 0;
  while ( $note = $r->fetch(PDO::FETCH_ASSOC)) {
    $note_total += $note['note'];
    $nombre_de_notes++;
  }
  $moyenne = $note_total/$nombre_de_notes;
  return $moyenne;
}

// fonction affichage_note_etoile

function affichage_note_etoile($id_salle){
  $moyenne = calcul_note_avis($id_salle);
  for ($i=0;  $i < $moyenne; $i++) {
    $resultat = $moyenne - $i;
    if ($resultat > 1) {
      echo '<i class="fas fa-star"></i>';
    }
    elseif ($resultat > 0) {
      echo '<i class="fas fa-star-half-alt"></i>';
    }

    // $resultat>1 ? echo '&#9733;' : echo '&#9734;';
    // echo '&#9733;';
  }
  $restant = 5 - $moyenne;
  if ( $restant > 1  ) {
    for ($i=0; $i < $restant  ; $i++) {
      echo '<i class="far fa-star"></i>';
      $restant--;
    }
  }
}
