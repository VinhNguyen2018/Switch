<?php require_once "inc/header.inc.php"; ?>
<?php

if( userConnect() ){ //Si l'internaute est connecté, on le redirige vers le profil.php

  header('location: '.URL.'profil.php');
  exit();
}

if ($_POST) {
  debug($_POST);

if( strlen( $_POST['pseudo'] ) <= 3 || strlen( $_POST['pseudo'] ) >= 20 ){
  //Si la taille du pseudo est inférieur ou égale a 3 OU qu il est supérieur à 20

    $error .= '<div class="alert alert-danger">Erreur taille pseudo</div>';
  }

  //Tester si le pseudo est disponible :
  $r = execute_requete(" SELECT pseudo FROM membre WHERE pseudo = '$_POST[pseudo]' " );

  if( $r->rowCount() >= 1 ){
  //Si le pseudo renvoie au moins 1 résultat, c'est que le pseudo est déjà attribué

    $error .= '<div class="alert alert-danger">Pseudo indisponible</div>';
  }

  //boucle sur les saisies afin de les passer dans la fonction addslashes
  foreach ($_POST as $key => $value) {

    $_POST[$key] = addslashes( $value );
    //addslashes() : permet d'accepter les apostrophes
  }

  //Cryptage du mot de passe :
  $_POST['mdp'] = password_hash( $_POST['mdp'], PASSWORD_DEFAULT );

  if( empty( $error ) ){ //Si la variable $error est vide, c'est qu'il n'y a pas eu d'erreurs donc on peut lancer l'inscription (insertion)

    execute_requete(" INSERT INTO membre(pseudo, mdp, nom, prenom, email, civilite, date_enregistrement) VALUES ('$_POST[pseudo]',
              '$_POST[mdp]',
              '$_POST[nom]',
              '$_POST[prenom]',
              '$_POST[email]',
              '$_POST[civilite]',
              NOW()
            )");
    echo "<div class='alert alert-success'>Inscription validée!
        <a href='". URL ."connexion.php' >Cliquez ici pour vous connecter</a>
      </div>";
  }
}
 ?>
<div class="container">
  <h1>inscription</h1>
  <?= $error; //affichage des erreurs ?>
  <form method="post">
    <label for="pseudo">Pseudo</label><br>
    <input type="text" class="form-control" name="pseudo" id="pseudo"><br>

    <label for="mdp">Mot de passe</label><br>
    <input type="text" class="form-control" name="mdp" id="mdp"><br>

    <label for="nom">Nom</label><br>
    <input type="text" class="form-control" name="nom" id="nom"><br>

    <label for="prenom">Prenom</label><br>
    <input type="text" class="form-control" name="prenom" id="prenom"><br>

    <label for="email">Email</label><br>
    <input type="text" class="form-control" name="email" id="email"><br>

    <label>Civilite</label><br>
    <input type="radio" name="civilite" value="m" checked>Homme<br>
    <input type="radio" name="civilite" value="f">Femme<br><br>

    <input type="submit" value="S'inscrire" class="btn btn-secondary">

  </form>
</div>



<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "inc/footer.inc.php"; ?>
