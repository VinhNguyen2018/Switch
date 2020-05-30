<?php require_once "inc/header.inc.php"; ?>
<?php

if( !userConnect() ){ //SI l'internaute N'EST PAS conencté

  //redirection vers la page de connexion
  header('location:connexion.php');

  exit(); //exit() : termine le script courant
}
//-----------------------------------------------------------

if( adminConnect() ){ //si on est connecté et que l'on est admin, on affiche un titre 'admnistrateur'

  $content .= '<h2 style="color:red;">ADMINISTRATEUR</h2>';
}

//-----------------------------------------------------------

//debug( $_SESSION );

$content .= '<h3>Bonjour ' . $_SESSION['membre']['pseudo'] . '</h3>';

$content .= '<p>Voici vos informations :</p>';

$content .= '<p>Votre nom : '. $_SESSION['membre']['nom'] .'</p>';
$content .= '<p>Votre prénom : '. $_SESSION['membre']['prenom'] .'</p>';
$content .= '<p>Votre email : '. $_SESSION['membre']['email'] .'</p>';


//----------------------------------------------------------------------------------------------
?>
<div class="container">
  <h1>PROFIL</h1>

  <?= $content; //affichage ?>

</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "inc/footer.inc.php"; ?>
