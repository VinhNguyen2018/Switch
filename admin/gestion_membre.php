<?php require_once "../inc/header.inc.php"; ?>
<?php
if( !adminConnect() ){ //Si l'admin N'EST PAS connecté, on le redirige vers la page connexion

  header('location:../connexion.php'); //redirection vers la page de conenxion
  exit();
}

//--------------TABLEAU AFFICHAGE DES MEMBRES-------------------//
$r = execute_requete("SELECT * FROM membre");
$content .= '<div class="table-responsive">';
$content .= '<table class="table table-dark">';
  $content .= '<thead>';
    $content .= '<tr>';
      for ($i=0; $i < $r->columnCount() ; $i++) {
        $colonne = $r->getColumnMeta( $i );
        if ($colonne['name'] != 'mdp') {
          $content .= '<th scope="col">' . $colonne['name'] . '</th>';
        }
      }
    $content .= '<th scope="col">Action</th>';
      while ($ligne = $r->fetch(PDO::FETCH_ASSOC) ) {
        $content .= '<tr>';
        // debug($ligne);
          foreach ($ligne as $key => $value) {
            if ($key == 'civilite') {
              $value == 'm' ? $content.= "<td>Homme</td>" : $content.= "<td>Femme</td>";
            }
            elseif ($key == 'statut'){
              $value == 0 ? $content.= "<td>membre</td>" : $content.= "<td>Admin</td>";
            }
            elseif ($key != 'mdp'){
              $content .= "<td>$value</td>";
            }
          }
          $content .= '<td>
                        <p><a href="'.URL.'">
                          <i class="fas fa-search"></i>
                        </a></p>
                        <p><a href="?action=edit&id_membre='. $ligne['id_membre'] .'">
                          <i class="far fa-edit"></i>
                        </a></p>
                        <p><a href="?action=delete&id_membre='. $ligne['id_membre'] .'" onclick="return( confirm(\'Es tu certain ?\') )" >
                        <i class="far fa-trash-alt"></i>
                        </a></p>
                      </td>';

        $content .= '</tr>';
      }
    $content .= '</tr>';
  $content .= '</thead>';
$content .= '</table>';
$content .= '</div>';







//--------------TABLEAU AFFICHAGE DES MEMBRES-------------------//
 ?>


<div class="container">
  <h1>Gestion des membres</h1>
  <?= $content ?>
  <form method="post">
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group">
          <label for="pseudo">Pseudo</label>
          <input type="text" id="pseudo" name="pseudo" class="form-control">
        </div>
        <div class="form-group">
          <label for="mdp">Mot de passe</label>
          <input type="text" id="mdp" name="mdp" class="form-control">
        </div>
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" id="nom" name="nom" class="form-control">
        </div>
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <input type="text" id="prenom" name="prenom" class="form-control">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" class="form-control">
        </div>
        <div class="form-group">
          <label for="civilite">Civilité</label>
          <select class="form-control" name="civilite" id="civilite">
            <option value="m">Homme</option>
            <option value="f">Femme</option>
          </select>
        </div>
        <div class="form-group">
          <label for="statut">Statut</label>
          <select class="form-control" name="statut" id="statut">
            <option value="0">Membre</option>
            <option value="1">Admin</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </form>
</div>







<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "../inc/footer.inc.php"; ?>
