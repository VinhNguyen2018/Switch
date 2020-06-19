<?php require_once "inc/header.inc.php"; ?>
<?php

//DECONNEXION :
if( isset($_GET['action']) && $_GET['action'] == 'deconnexion' ){
//si il y a une 'action' dan l'URL ET que cette 'action' est égale à 'deconnexion', alors on détruit la session
  session_destroy();
  header('location:'.URL.'connexion.php');
  exit();
}
//-----------------------------------------------------------------

if( userConnect() ){ //si l'internaute est connecté, on le redirige vers profil.php

  header('location:'.URL.'profil.php');
  exit();
}

//-----------------------------------------------------------------
if( $_POST ){ //si on a valider le formulaire
  //debug( $_POST );

  $r = execute_requete(" SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]' ");
  //on récupère les infos du membre correspondant au pseudo renseigné

  if( $r->rowCount() >= 1  ){
  //si il y a une correspondance dans la table 'membre', '$r' renverra '1' ligne de résultat

    $membre = $r->fetch( PDO::FETCH_ASSOC );
    //debug( $membre );

    //verification du mdp : si le mdp est correct, on renseigne des informations dans notre fichier de session
    if( password_verify( $_POST['mdp'] , $membre['mdp'] ) ){
      //password_verify( "mdp posté", "clé de cryptage")

      $_SESSION['membre']['id_membre'] = $membre['id_membre'];
      $_SESSION['membre']['pseudo'] = $membre['pseudo'];
      $_SESSION['membre']['mdp'] = $membre['mdp'];
      $_SESSION['membre']['prenom'] = $membre['prenom'];
      $_SESSION['membre']['nom'] = $membre['nom'];
      $_SESSION['membre']['email'] = $membre['email'];
      $_SESSION['membre']['civilite'] = $membre['civilite'];
      $_SESSION['membre']['date_enregistrement'] = $membre['date_enregistrement'];
      $_SESSION['membre']['statut'] = $membre['statut'];

      //debug( $_SESSION );
      //redirection vers la page profil :
      header('location:'.URL.'profil.php');
    }
    else{

      $error .= '<div class="alert alert-danger">Erreur mot de passe</div>';
    }
  }
  else{

    $error .= '<div class="alert alert-danger">Erreur Pseudo</div>';
  }
}

//-----------------------------------------------------------------------------------
?>
<div class="container">
  <div class="stat-box">
    <h1>CONNEXION</h1>

    <?= $error; // affichage des erreurs ?>

    <form method="post">
      <label>Pseudo</label><br>
      <input type="text" class="form-control" name="pseudo"><br>

      <label>Mot de passe</label><br>
      <input type="text" class="form-control" name="mdp"><br>

      <input type="submit" value="Connexion" class="btn btn-secondary">
    </form>

  </div>

</div>

<?php require_once "inc/footer.inc.php"; ?>
