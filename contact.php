<?php require_once "inc/header.inc.php"; ?>
<?php

  userConnect() ? $email = $_SESSION['membre']['email'] : $email='';

if (!empty( $_POST )) {
  // debug($_POST);
  foreach ($_POST as $key => $value) { //ici, on passe toutes les informations postées du formulaire dans les fonctions htmlentities() et addslashes() :

    $_POST[$key] = htmlentities( addslashes( $value ) );
  }
  if ( $_POST ) {
    alertReponse();
    $r = execute_requete("SELECT email FROM membre WHERE statut = 1");
    $email_admin = $r->fetch(PDO::FETCH_ASSOC);
    $to      = $email_admin['email'];
    $subject = $_POST['objet'];
    $message = $_POST['message'];
    $headers = 'From: '. $_POST['email'] . "\r\n" .
     'Reply-To: '. $_POST['email'] . "\r\n" .
     'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
    // debug($to);
  }
}
 ?>
<div class="container">
  <div class="stat-box" style="height:80vh;">
    <h1>Formulaire de contact</h1>
    <form method="post">
      <div class="form-group">
        <label for="objet">Objet du message</label>
        <input type="text" name="objet" id="objet" class="form-control">
      </div>
      <div class="form-group">
        <label for="email">Votre adresse email</label>
        <input type="email" name="email" id="email" value="<?= $email ?>" placeholder="Remplir votre email" class="form-control">
      </div>
      <div class="form-group">
        <label for="message">Votre message</label>
        <textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>
      </div>
      <input type="submit" value="Envoyez" class="btn btn-primary">
    </form>
  </div>
</div>
<?php require_once "inc/footer.inc.php"; ?>
