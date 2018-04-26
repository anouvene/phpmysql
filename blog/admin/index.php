<?php
  include_once ('../inc/header.inc.php');
  if (isset($_SESSION['membre'])):
    extract($_SESSION);

    echo '<div class="txt-light-green text-center" role="contentinfo">Bonjour, <span class="txt-orange">' . strtoupper($nickname) . '</span>. Bienvenue dans votre espace compte.</div>';
    echo '<div class="txt-light-green text-center" role="contentinfo">En tant que membre, vous pouvez écrire des commentaires sur nos articles publiés.</div>';
    echo '<div class="txt-light-green text-center" role="contentinfo">Si vous n\'êtes pas <span class="txt-orange">' . strtoupper($nickname) . '</span>, Veuiller vous => <a href="deconnexion.php" title="Se déconnecter" class="txt-orange">déconnecter</a></div>';
?>
      <div class="misc">
        <?php
        if (checkPermissions($_SESSION['membre'], 'create_post')):
        $errors = ajouterArticle();
        ?>
        <h1>Ajouter un article !</h1>
        <div class="jumbotron--bis curve">
          <form method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
            <div class="form-group <?=(isset($errors['titre']))?'has-error':'has-success'?>">
              <label class="control-label" for="inputSuccess1">Libéllé de l'article *</label>
              <input type="text" class="form-control" id="inputSuccess1" name="titre" value="<?=(isset($_POST['titre']))?$_POST['titre']:''?>">
              <span class="help-block">* Libéllé de l'article (obligatoire)</span>
            </div>
            <div class="form-group <?=(isset($errors['accroche']))?'has-error':'has-success'?>">
              <label class="control-label" for="inputSuccess2">Extrait de l'article *</label>
              <textarea name="accroche" class="form-control" id="inputSuccess2"><?=(isset($_POST['accroche']))?$_POST['accroche']:''?></textarea>
              <span class="help-block">Extrait de l'article (obligatoire)</span>
            </div>
            <div class="form-group <?=(isset($errors['contenu']))?'has-error':'has-success'?>">
              <label class="control-label" for="inputSuccess3">Description complète de l'article *</label>
              <textarea name="contenu" class="form-control contenu-post" id="inputSuccess3"><?=(isset($_POST['contenu']))?$_POST['contenu']:''?></textarea>
              <span class="help-block">Corps de l'article (obligatoire)</span>
            </div>
            <div class="form-group <?=(isset($errors['imgFile']))?'has-error':'has-success'?>">
              <label class="control-label" for="inputSuccess4">Photo article *</label>
              <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
              <input type="file" name="imgFile" class="form-control" id="inputSuccess4">
              <span class="help-block">photo de l'article (obligatoire)</span>
            </div>
            <input class="btn btn-default" name="valid" type="submit" value="Ajouter">
          </form>
          <div class="clearfix"></div>
        </div>
        <?php
        endif;
        ?>

        <?php

        if (checkPermissions($_SESSION['membre'], 'edit_comments')):
          $commentairesPrep = commentaires();
          $commentairesPrep->bindColumn('id', $id_commentaire);
          $commentairesPrep->bindColumn('id_membre', $id_membre);
          $commentairesPrep->bindColumn('pseudo', $pseudo);
          $commentairesPrep->bindColumn('commentaire', $commentaire);
          $commentairesPrep->bindColumn('publication', $publication);
          $commentairesPrep->bindColumn('publie', $publie);
        ?>
          <?php if ($commentairesPrep->rowCount() > 0): ?>
          <!-- Les commentaires postés du membre -->
          <div class="jumbotron--bis curve">
            <h1>Tous les commentaires des membres</h1>
            <!--Membres comments-->
            <section class="article-comments">
              <?php while ($commentairesPrep->fetch(PDO::FETCH_BOUND)) : ?>
                <div class="comment">
                  <a data-comment-info="<?=$id_membre.'_'.$id_commentaire?>" href="#" class="lock-unlock <?=($publie=='non')?'btn-lock':'btn-unlock'?>" title="Autoriser la publication"><i></i></a> <span id="<?='m'.$id_membre.'c'.$id_commentaire?>"><?=($publie=='non')?'<i class="unpublished">Commentaire à valider</i>':'<i class="published">Commentaire validé et publié</i>'?></span>  <?=publication($publication, $pseudo, '')?>
                  <p><?=$commentaire?></p>
                </div>
              <?php endwhile ?>
              <div class="clearfix"></div>
            </section>
          </div>
        <?php
          endif;
        endif;
        ?>
      </div>
<?php
  else:
    header('Location: ./../inscription.php');
  endif;

  // Footer
  include_once (realpath(__DIR__. '/../inc/footer.inc.php'));
?>