<?php
include_once './../inc/header.inc.php';
/**
 * Mise à jour compte utilisateur
 */


?>
      <div class="misc">
<?php
// Si membre non connecté : création compte
if ( (isset($_SESSION['membre'])) ):
  $errors = inscriptionUpdate($_SESSION['membre']);
  $membre = afficheMembre($_SESSION['membre']);
?>
          <div class="welcome">
            <h1>Bienvenue <?=$_SESSION['nickname']?> !</h1>
            <div class="welcome__content">
              <p>Adresse e-mail : <?=$_SESSION['email']?></p>
            </div>
          </div>
          <!-- Les coordonnées du membre -->
          <h1>Mise à jour de vos données personnelles</h1>
          <div class="jumbotron--bis curve">
            <form method="post" action="<?=$_SERVER['PHP_SELF']?>">
              <div class="form-group  <?=(isset($errors['nickname']))?'has-error':'has-success'?>">
                <input type="text" class="form-control" name="nickname" placeholder="Pseudo *" value="<?=(isset($_POST['nickname']))?$_POST['nickname']:$membre['pseudo']?>">
                <span class="help-block">* Pseudo d'au moins 2 caractères (obligatoire)</span>
              </div>
              <div class="form-group  <?=(isset($errors['email']))?'has-error':'has-success'?>">
                <input type="text" class="form-control" name="email" placeholder="Adresse e-mail *" value="<?=(isset($_POST['email']))?$_POST['email']:$membre['email']?>">
                <span class="help-block">* Adresse e-mail (obligatoire)</span>
              </div>
              <div class="form-group  <?=(isset($errors['emailconf']))?'has-error':'has-success'?>">
                <input type="text" class="form-control" name="emailconf" placeholder="Confirmation de l'e-mail *" value="<?=(isset($_POST['emailconf']))?$_POST['emailconf']:$membre['email']?>">
                <span class="help-block">* Confirmation de l'e-mail (obligatoire)</span>
              </div>
              <div class="form-group  <?=(isset($errors['password']))?'has-error':'has-success'?>">
                <input type="password" class="form-control" name="password" placeholder="Mot de passe *" value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>">
                <span class="help-block">* Mot de passe composé de 6 à 49 caractères (obligatoire)</span>
              </div>
              <div class="form-group  <?=(isset($errors['passwordconf']))?'has-error':'has-success'?>">
                <input type="password" class="form-control" name="passwordconf" placeholder="Confirmation du mot de passe *" value="<?php if(isset($_POST['passwordconf'])) echo $_POST['passwordconf'] ?>">
                <span class="help-block">* Confirmation du mot de passe (obligatoire)</span>
              </div>
              <input type="submit" class="btn btn-default" name="" value="Mettre à jour">
            </form>
            <div class="clearfix"></div>
          </div>

          <?php
          $p = 'compte'; // var globale pour la fonction commentaire ci-dessous
          $commentairesPrep = commentaires();

          if($commentairesPrep->rowCount() > 0):
            $commentairesPrep->bindColumn('titre', $titre);
            $commentairesPrep->bindColumn('commentaire', $commentaire);
            $commentairesPrep->bindColumn('publication', $publication); 
          ?>
            
            <!-- Les commentaires postés du membre -->
            <div class="jumbotron--bis curve">
              <h1>Vos commentaires</h1>
              <!--Membres comments-->
              <section class="article-comments">
                <?php while ($commentairesPrep->fetch(PDO::FETCH_BOUND)) : ?>
                  <div class="comment">
                    <?=publication($publication, '', $titre)?>
                    <p><?=$commentaire?></p>
                  </div>
                <?php endwhile ?>
                <div class="clearfix"></div>
              </section>
            </div>
          <?php
          endif;
          ?>
<?php else: ?>
<?php header('Location: ./../inscription.php'); ?>
<?php endif; ?>
      </div>
<?php
include_once './../inc/footer.inc.php';
?>
