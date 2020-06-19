<?php require_once "inc/header.inc.php"; ?>

<div class="container">

    <div class="row">

      <div class="col-lg-3">
        <form method="post">
          <h5 class="my-4">Catégorie</h5>
          <!-- <div class="list-group">
            <a href="#" class="list-group-item">Réunion</a>
            <a href="#" class="list-group-item">Bureau</a>
            <a href="#" class="list-group-item">Formation</a>
          </div> -->
            <div class="form-group">
              <select name="categorie" id="categorie" class="form-control">
                <option value="reunion">Réunion</option>
                <option value="bureau">Bureau</option>
                <option value="formation">Formation</option>
              </select>
            </div>
          <h5 class="my-4">Ville</h5>
          <div class="form-group">
            <select name="ville" id="ville" class="form-control">
              <option value="Paris">Paris</option>
              <option value="Milan">Milan</option>
              <option value="Francfort">Francfort</option>
            </select>
          </div>
          <h5 class="my-4">Capacité</h5>
          <div class="form-group">
            <select name="capacite" id="capacite" class="form-control">
            <?php
              $r = execute_requete("SELECT capacite FROM salle ORDER BY capacite ASC");
              while ( $capacite = $r->fetch(PDO::FETCH_ASSOC) ) {
                echo '<option value="'.$capacite['capacite'].'">'.$capacite['capacite'].'</option>';
              }
             ?>
            </select>
          </div>
          <div class="slidecontainer">
            <?php
              $r = execute_requete("SELECT MIN(prix) AS min, MAX(prix) as max FROM produit");
              $prix = $r->fetch(PDO::FETCH_ASSOC);
              // debug($test);
             ?>
            <h5 class="my-4">Prix</h5>
            <input type="range" min="<?= intval($prix['min'])  ?>" max="<?= intval($prix['max'])  ?>" class="slider" name="prix" id="prix">
            <p>Prix maximum: <span id="demo"></span>€</p>
          </div>

          <div class="form-group">
            <h5>Période</h5>
            <label for="date_arrivee"><i>Date d'arrivée</i></label>
            <input type="date" name="date_arrivee" id="date_arrivee" class="form-control">
            <label for="date_depart"><i>Date de depart</i></label>
            <input type="date" name="date_depart" id="date_depart" class="form-control">
          </div>

          <input type="submit" class="btn btn-primary" value="Filtrer">
        </form>
      </div>
      <!-- /.col-lg-3 -->

      <div class="col-lg-9">

        <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
          <div class="carousel-inner" role="listbox">
            <?php
              $r = execute_requete("SELECT photo,titre FROM salle");
              $photos = $r->fetchAll(PDO::FETCH_ASSOC);
              // debug(gettype($photos));
              foreach ($photos as $key => $value) {
               if ($key == 0) {
                 echo '<div class="carousel-item active">';
                  echo '<img class="d-block img-fluid" src="' . $value['photo'] . '" alt="photo salle/bureau ' . $value['titre'] . '">';
                 echo '</div>';
               }
               else {
                echo '<div class="carousel-item ">';
                  echo '<img class="d-block img-fluid" src="' . $value['photo'] . '" alt="photo salle/bureau ' . $value['titre'] . '">';
                 echo '</div>';
               }
              }
             ?>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Précedent</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Suivant</span>
          </a>
        </div>

        <div class="row">
        <?php
        if ($_POST) {
          $r = execute_requete("SELECT date_arrivee, salle.id_salle, date_depart, prix, salle.titre, description, salle.photo, salle.categorie, id_produit, salle.ville FROM produit, salle WHERE produit.id_salle = salle.id_salle
          AND produit.etat = 'libre'
          AND date_arrivee > NOW()
          AND salle.categorie = '$_POST[categorie]'
          AND salle.ville = '$_POST[ville]'
          AND salle.capacite = '$_POST[capacite]'
          AND prix >= '$_POST[prix]'
           ");
        }
        else {
          $r = execute_requete("SELECT date_arrivee, salle.id_salle, date_depart, prix, salle.titre, description, salle.photo, salle.categorie, id_produit FROM produit, salle WHERE produit.id_salle = salle.id_salle
            AND produit.etat = 'libre'
            AND date_arrivee > NOW() ");
        }
        while ($produit = $r->fetch(PDO::FETCH_ASSOC)) {
          // debug($produit);
          echo '<div class="col-lg-4 col-md-6 mb-4">';
            echo '<div class="card h-100">';
              echo '<a href="#"><img class="card-img-top" src="' . $produit['photo'] . '" alt=""></a>';
              echo '<div class="card-body">';
                echo '<h5 class="card-title">';
                  echo '<a href="#">';
                    if ($produit['categorie'] == 'bureau'){
                      echo "Bureau $produit[titre]";
                    }
                    else{
                      echo "Salle $produit[titre]";
                    }
                  echo '</a>';
                echo '</h5>';
                echo "<h5>$produit[prix] &euro;</h5>";
                echo '<p class="d-inline-block text-truncate" style="max-width: 150px;">'.$produit['description'].'</p>';
                echo '<p style="font-size:12px;">
                <i class="far fa-calendar-alt"></i>
                '. date('d/m/Y' ,strtotime($produit['date_arrivee'])). ' au
                <i class="far fa-calendar-alt"></i>
                '. date('d/m/Y', strtotime($produit['date_depart'])) . '
                      </p>';
              echo '</div>';
              echo '<div class="card-footer">';
                echo '<small class="text-muted">';
                  affichage_note_etoile($produit['id_salle']);
                echo '</small>';
                echo '<a href="' .URL.'fiche_produit.php?id_produit='. $produit['id_produit'] . '" class="float-right"><i class="fas fa-search"></i> Voir</a>';
              echo '</div>';
            echo '</div>';
          echo '</div>';
        }




         ?>


          <!-- <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
              <a href="#"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
              <div class="card-body">
                <h4 class="card-title">
                  <a href="#">Item Three</a>
                </h4>
                <h5>$24.99</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam aspernatur!</p>
              </div>
              <div class="card-footer">
                <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
              </div>
            </div>
          </div> -->


        </div>
        <!-- /.row -->

      </div>
      <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

<script>
  var slider = document.getElementById("prix");
  var output = document.getElementById("demo");
  output.innerHTML = slider.value; // Display the default slider value

  // Update the current slider value (each time you drag the slider handle)
  slider.oninput = function() {
    output.innerHTML = this.value;
  }
</script>

<?php require_once "inc/footer.inc.php"; ?>
