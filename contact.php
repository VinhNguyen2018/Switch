<?php require_once "inc/header.inc.php"; ?>
<?php

  userConnect() ? $email = $_SESSION['membre']['email'] : $email='';

  if ( isset($_POST) ) {
    alertReponse();
  }
 ?>
<div class="container">
  <div class="stat-box" style="height:80vh;">
    <h1>Formulaire de contact</h1>
    <form method="post">
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
