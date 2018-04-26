<?php
include_once './inc/header.inc.php';
require_once './inc/blog.inc.php';

$errors = connexion();

?>
        <div class="misc">
          <h1>Connectez-vous !</h1>
          <div class="jumbotron--bis curve">
            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
              <div class="form-group <?=(isset($errors['nickname']))?'has-error':'has-success'?>">
                <input type="text" class="form-control" name="nickname" placeholder="Pseudo *" value="<?=(isset($_POST['nickname']))?$_POST['nickname']:''?>">
                <span class="help-block">Votre pseudo doit comporter entre 2 et 50 caractères</span>
              </div>
              <div class="form-group <?=(isset($errors['password']))?'has-error':'has-success'?>">
                <input type="password" class="form-control" name="password" placeholder="Mot de passe *" value="<?=(isset($_POST['password']))?$_POST['password']:''?>">
                <span class="help-block">Votre mot de passe doit comporter au moins 6 caractères</span>
              </div>
              <input class="btn btn-default" type="submit" value="Me connecter!">
            </form>
            <div class="clearfix"></div>
           </div>
        </div>
  <?php

// Stopper le curseur
// $connex->closeCursor();

// Fermer la connexion
$pdoConf = null;

// Footer
include_once './inc/footer.inc.php';

?>