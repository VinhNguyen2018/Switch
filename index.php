<?php require_once "inc/header.inc.php"; ?>


<div class="container">

    <div class="row">

      <div class="col-lg-3">

        <h1 class="my-4">Catégorie</h1>
        <div class="list-group">
          <a href="#" class="list-group-item">Réunion</a>
          <a href="#" class="list-group-item">Bureau</a>
          <a href="#" class="list-group-item">Formation</a>
        </div>

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
        $r = execute_requete("SELECT date_arrivee, salle.id_salle, date_depart, prix, salle.titre, description, salle.photo, salle.categorie FROM produit, salle WHERE produit.id_salle = salle.id_salle");
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
              // a changer
                echo '<small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>';
                // a changer
                echo '<a href="#" class="float-right"><i class="fas fa-search"></i> Voir</a>';
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

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "inc/footer.inc.php"; ?>
