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

