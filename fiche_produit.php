<?php require_once "inc/header.inc.php"; ?>

<?php
// debug($_GET['action']);
if( isset($_GET['id_produit']) ){ //Si il existe 'id_produit' dans mon URL c'est que j'ai bien sélectionné un produit

  $r = execute_requete("SELECT date_arrivee, salle.id_salle, date_depart, prix, salle.titre, salle.description, salle.photo, salle.categorie, salle.capacite, salle.adresse, salle.cp, salle.ville, etat FROM produit, salle WHERE produit.id_salle = salle.id_salle AND id_produit = '$_GET[id_produit]'");
}
else{ //Sinon, redirection vers l'accueil

  header('location:'.URL.'index.php');
  exit(); //quitte le script courant
}

$produit = $r->fetch(PDO::FETCH_ASSOC);
  // debug( $produit );
$id_salle = $produit['id_salle'];

//RESERVATION-------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'booking') {
  if ($produit['etat'] == 'reservation') {
    echo '<div class="alert alert-danger" role="alert">Le produit n\'est pas disponible.</div>';
  }
  else{
    $id_membre = $_SESSION['membre']['id_membre'];
    execute_requete("INSERT INTO commande(id_membre, id_produit, date_enregistrement) VALUES
      ( '$id_membre',
        '$_GET[id_produit]',
        NOW() )  ");
    // debug($_SESSION['membre']['id_membre']);
    execute_requete("UPDATE produit SET etat = 'reservation' WHERE id_produit = '$_GET[id_produit]' ");
    header('location:'.URL.'profil.php');
    exit(); //quitte le script courant
  }
}
 ?>


<!-- Page Content -->
  <div class="container">
    <div class="top-product" style="display:flex; justify-content:space-between;">
      <h2>
        <?= $produit['categorie'] == 'bureau' ? 'Bureau ' . $produit['titre']   : 'Salle' . ' ' . $produit['titre']  ?>
        <span class="text-warning"><?= affichage_note_etoile($id_salle); ?></span>
      </h2>
      <?php if (userConnect()): ?>
        <a class="btn btn-success my-auto" href="?action=booking&id_produit=<?= $_GET['id_produit'] ?>" role="button" onclick="confirm('Validez votre réservation ?')">Réserver</a>
      <?php else : ?>
        <p style="margin:auto 0;"><a href="<?= URL . 'connexion.php' ?>" >Connectez-vous </a>pour réserver</p>
      <?php endif ?>
    </div>
    <hr>
    <div class="row">
      <div class="col-lg-8">
        <img class="card-img-top img-fluid" src="<?= $produit['photo'] ?>" alt="" style="height:100%;">
      </div>
      <div class="col-lg-4">
        <h5>Description</h5>
        <p><?= $produit['description']  ?></p>
        <h5>Localisation</h5>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.0218448823603!2d2.306815315570264!3d48.8768601073603!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fc77cc1d6e7%3A0xe09ff8b74b2e3e26!2s30%20Rue%20de%20Monceau%2C%2075008%20Paris!5e0!3m2!1sfr!2sfr!4v1591195139212!5m2!1sfr!2sfr" width="400" height="300" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
      </div>
    </div>
    <!-- /.row -->
    <h5>Informations complémentaires</h5>
    <div class="row">
      <div class="col-lg-4">
        <p>
          <i class="far fa-calendar-alt"></i>
          Arrivée : <?= date('d/m/Y' ,strtotime($produit['date_arrivee'])) ?>
        </p>
        <p>
          <i class="far fa-calendar-alt"></i>
          Départ : <?= date('d/m/Y' ,strtotime($produit['date_depart'])) ?>
        </p>
      </div>
      <div class="col-lg-4">
        <p>
          <i class="fas fa-users"></i>
          Capacité : <?= $produit['capacite'] ?>
        </p>
        <p>
          <i class="far fa-building"></i>
          Catégorie :  <?= $produit['categorie'] ?>
        </p>
      </div>
      <div class="col-lg-4">
        <p>
          <i class="fas fa-map-pin"></i>
          Adresse : <?= $produit['adresse'] . ', ' . $produit['cp']
          . ', ' . $produit['ville']  ?>
        </p>
        <p>
          <i class="fas fa-money-bill"></i>
          Tarif :  <?= $produit['prix'] ?> €
        </p>
      </div>
    </div>
    <!-- /.row -->
  <h4>Autres produits</h4>
  <hr>
  <div class="row">
    <?php
      $r = execute_requete("SELECT salle.photo, id_produit, salle.titre FROM produit, salle WHERE salle.id_salle = produit.id_salle AND NOT id_produit = '$_GET[id_produit]' ORDER BY rand() LIMIT 4");
      while ( $produit = $r->fetch(PDO::FETCH_ASSOC) ) {
        echo '<div class="col-lg-3">';

            echo '<a href="' . URL . 'fiche_produit.php?id_produit=' . $produit['id_produit'] . '">
                    <img src="' . $produit['photo'] . '" alt="' . $produit['titre'] . '" class="img-thumbnail">
                  </a>';

        echo '</div>';
      }

     ?>
  </div>
  <hr>
  <div class="bottom-product">
    <?php if (userConnect()): ?>
      <a href="<?= URL . 'avis.php?id_salle=' . $id_salle  ?>">Déposer un commentaire et une note</a>
    <?php else : ?>
      <p>Pour laisser un commentaire sur le produit, <a href="<?= URL . 'connexion.php'  ?>"> connectez-vous</a></p>

    <?php endif ?>
    <a href="<?= URL  ?>">Retour vers le catalogue</a>
  </div>
  <!-- /.container -->
  </div>
    <!-- <div class="card mt-4">
      <img class="card-img-top img-fluid" src="http://placehold.it/900x400" alt="">
      <div class="card-body">
        <h3 class="card-title">Product Name</h3>
        <h4>$24.99</h4>
        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente dicta fugit fugiat hic aliquam itaque facere, soluta. Totam id dolores, sint aperiam sequi pariatur praesentium animi perspiciatis molestias iure, ducimus!</p>
        <span class="text-warning">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
        4.0 stars
      </div>
    </div>

    <div class="card card-outline-secondary my-4">
      <div class="card-header">
        Product Reviews
      </div>
      <div class="card-body">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <a href="#" class="btn btn-success">Leave a Review</a>
      </div>

    </div>
 -->
  </div>
  <!-- /.container -->




<?php require_once "inc/footer.inc.php"; ?>
