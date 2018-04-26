<?php
include_once './inc/header.inc.php';
if(!isset($_SESSION['membre'])):
  $errors = inscription();
?>
      <div class="misc">
        <?php
        // Si membre non connecté : création compte
        if ( !(isset($_SESSION['membre'])) ):
        ?>
        <h1>Inscription sur PHP/MySQL !</h1>
        <div class="jumbotron--bis curve">
          <form method="post" action="<?=$_SERVER['PHP_SELF']?>">
            <div class="form-group  <?=(isset($errors['nickname']))?'has-error':'has-success'?>">
              <input type="text" class="form-control" name="nickname" placeholder="Pseudo *" value="<?php if(isset($_POST['nickname'])) echo $_POST['nickname']; ?>">
              <span class="help-block">* Pseudo d'au moins 2 caractères (obligatoire)</span>
            </div>
            <div class="form-group  <?=(isset($errors['email']))?'has-error':'has-success'?>">
              <input type="text" class="form-control" name="email" placeholder="Adresse e-mail *" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
              <span class="help-block">* Adresse e-mail (obligatoire)</span>
            </div>
            <div class="form-group  <?=(isset($errors['emailconf']))?'has-error':'has-success'?>">
              <input type="text" class="form-control" name="emailconf" placeholder="Confirmation de l'e-mail *" value="<?php if(isset($_POST['emailconf'])) echo $_POST['emailconf']; ?>">
              <span class="help-block">* Confirmation de l'e-mail (obligatoire)</span>
            </div>
            <div class="form-group  <?=(isset($errors['password']))?'has-error':'has-success'?>">
              <input type="password" class="form-control" name="password" placeholder="Mot de passe *">
              <span class="help-block">* Mot de passe composé de 6 à 49 caractères (obligatoire)</span>
            </div>
            <div class="form-group  <?=(isset($errors['passwordconf']))?'has-error':'has-success'?>">
              <input type="password" class="form-control" name="passwordconf" placeholder="Confirmation du mot de passe *">
              <span class="help-block">* Confirmation du mot de passe (obligatoire)</span>
            </div>
              <input type="submit" class="btn btn-default" value="M'inscrire!">
          </form>
          <div class="clearfix"></div>
        </div>
        <?php endif; ?>
      </div>
<?php
else:
  header('Location: ./admin/inscriptionUpdate.php');
endif;
// Footer
include_once './inc/footer.inc.php';

?>