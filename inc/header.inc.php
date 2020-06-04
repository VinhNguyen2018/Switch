<?php require_once "init.inc.php"; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Switch - Votre location de salle pour travailler en quelques clics</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CDN BOOTSTRAP -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- CDN FONT AWESOME-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <!-- CSS PERSO -->
  <link rel="stylesheet" href="<?= URL . 'assets/css/style.css'  ?>">
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= URL ?>">SWITCH</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Accueil <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Qui sommes-nous ?</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
      <div class="nav-item dropdown text">
        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle"></i> Espace membre
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php if( userConnect() && !adminConnect() ) : ?>
            <a class="dropdown-item" href="<?= URL ?>connexion.php?action=deconnexion">Déconnexion</a>
            <a class="dropdown-item" href="<?= URL ?>profil.php">Profil</a>
          <?php elseif( adminConnect() ) : ?>
            <a class="dropdown-item" href="<?= URL ?>connexion.php?action=deconnexion">Déconnexion</a>
            <a class="dropdown-item" href="<?= URL ?>profil.php">Profil</a>
            <div class="dropdown-divider"></div>
            <span class="dropdown-item"> <strong>Back Office</strong></span>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= URL ?>admin/gestion_salle.php">Gestion salle</a>
            <a class="dropdown-item" href="<?= URL ?>admin/gestion_produit.php">Gestion produit</a>
            <a class="dropdown-item" href="<?= URL ?>admin/gestion_membre.php">Gestion membre</a>
            <a class="dropdown-item" href="<?= URL ?>admin/gestion_avis.php">Gestion avis</a>
            <a class="dropdown-item" href="<?= URL ?>admin/gestion_commande.php">Gestion commande</a>
            <a class="dropdown-item" href="<?= URL ?>admin/statistiques.php">Statistiques</a>

          <?php else : ?>
            <a class="dropdown-item" href="<?= URL ?>inscription.php">Inscription</a>
            <a class="dropdown-item" href="<?= URL ?>connexion.php">Connexion</a>
          <?php endif ; ?>
        </div>
      </div>
    </div>
  </nav>
</header>

<main>
