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

//----------------- INSERTION----------------------------

if (!empty( $_POST )) {
    // debug($_POST);
    foreach ($_POST as $key => $value) { //ici, on passe toutes les informations postées du formulaire dans les fonctions htmlentities() et addslashes() :

    $_POST[$key] = htmlentities( addslashes( $value ) );
    }
//Modification membres --------------------------------------------
  if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    execute_requete("UPDATE membre SET
    pseudo = '$_POST[pseudo]',
    prenom = '$_POST[prenom]',
    nom = '$_POST[nom]',
    email = '$_POST[email]',
    civilite = '$_POST[civilite]',
    statut = '$_POST[statut]'
    WHERE id_membre = '$_GET[id_membre]'");

    header('location:gestion_membre.php');
      exit();
    }
  }

  if ( isset($_GET['id_membre']) ) {
    $r = execute_requete("SELECT * FROM membre WHERE id_membre = '$_GET[id_membre]'");
    $membre_actuel = $r->fetch(PDO::FETCH_ASSOC);
  }
  $pseudo = ( isset($membre_actuel['pseudo']) ) ? $membre_actuel['pseudo'] : '';
  $nom = ( isset($membre_actuel['nom']) ) ? $membre_actuel['nom'] : '';
  $prenom = ( isset($membre_actuel['prenom']) ) ? $membre_actuel['prenom'] : '';
  $email = ( isset($membre_actuel['email']) ) ? $membre_actuel['email'] : '';
  $civilite_m = ( isset($membre_actuel['civilite']) &&  $membre_actuel['civilite'] == 'm') ? 'selected' : '';
  $civilite_f = ( isset($membre_actuel['civilite']) &&  $membre_actuel['civilite'] == 'f') ? 'selected' : '';
  $statut_membre = ( isset($membre_actuel['statut']) &&  $membre_actuel['statut'] == 0) ? 'selected' : '';
  $statut_admin = ( isset($membre_actuel['statut']) &&  $membre_actuel['statut'] == 1) ? 'selected' : '';

 ?>


<div class="container">
  <h1>Gestion des membres</h1>
  <?= $content ?>
  <?php if( isset($_GET['action']) && $_GET['action'] == 'edit') : ?>
  <form method="post">
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group">
          <label for="pseudo">Pseudo</label>
          <input type="text" id="pseudo" name="pseudo" class="form-control" value="<?= $pseudo ?>">
        </div>
        <div class="form-group">
          <label for="mdp">Mot de passe</label>
          <input type="password" id="mdp" name="mdp" class="form-control" value ="**********" disabled>
        </div>
        <div class="form-group">
          <label for="nom">Nom</label>
          <input type="text" id="nom" name="nom" class="form-control" value="<?= $nom ?>">
        </div>
        <div class="form-group">
          <label for="prenom">Prénom</label>
          <input type="text" id="prenom" name="prenom" class="form-control" value="<?= $prenom ?>">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>">
        </div>
        <div class="form-group">
          <label for="civilite">Civilité</label>
          <select class="form-control" name="civilite" id="civilite">
            <option value="m" <?= $civilite_m ?> >Homme</option>
            <option value="f" <?= $civilite_f ?> >Femme</option>
          </select>
        </div>
        <div class="form-group">
          <label for="statut">Statut</label>
          <select class="form-control" name="statut" id="statut">
            <option value="0"  <?= $statut_membre ?> >Membre</option>
            <option value="1" <?= $statut_admin ?> >Admin</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </form>
<?php endif ; ?>
</div>







<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once "../inc/footer.inc.php"; ?>
