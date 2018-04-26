<?php
$bodyID = 'product-page'; // Identifiant body
include_once './inc/header.inc.php';// Header

if ( !empty($_GET['id']) && isset($_GET['id']) ):
  $article = article((int)$_GET['id']);
  $commentairesPrep = commentaires();

?>
        <article>
          <!--Article details-->
          <section class="article-details">
            <div class="article__img">
              <img src="img/img_bis/<?=$article['image']?>" alt="">
            </div>
            <div class="article__txt">
                <?=$article['publication']?>
                <h1><?=$article['titre']?></h1>
              <details>
                <summary><?=$article['accroche']?></summary>
                <p><?=$article['contenu']?></p>
              </details>

            </div>
          </section>
          <!--Membres comments-->
          <section class="article-comments">
            <?php
            if ($commentairesPrep->rowCount()):
              /* Liaison des résultats à des variables
              ---------------------------------------*/
              $commentairesPrep->bindColumn('pseudo', $pseudo);
              $commentairesPrep->bindColumn('commentaire', $commentaire);
              $commentairesPrep->bindColumn('publication', $publication);
            ?>
              <h1>Commentaires (<?=$commentairesPrep->rowCount();?>)</h1>
              <?php while ($commentairesPrep->fetch(PDO::FETCH_BOUND)) : ?>
              <div class="comment">
                <?=publication($publication, $pseudo, '')?>
                <p><?=$commentaire?></p>
              </div>
              <?php endwhile; ?>
            <?php endif; ?>

            <!--Post a comment-->
            <?php
            if (isset($_SESSION['membre'])):
              $msg = verifComment();
            ?>
            <a name="comment" id="comment"></a>
            <form class="comment-post" method="post" action="#comment">
              <?=implode('', $msg)?>
              <?php
              if(empty($msg)){
                if(isset($_POST['commentaire'])){
                  //$_SESSION['commentaire'] = $_POST['commentaire']; // transmettre commentaire posté au fichier de traitement 'ajouterCommentaire.php'
                  //header("Location: ajouterCommentaire.php?id=" . $_GET['id'] );
                  include 'ajouterCommentaire.php' ;
                }
              }
              ?>
              <?=(isset($_SESSION['success_comment']))?$_SESSION['success_comment']:''?>
              <div class="form-group <?=(isset($msg['error_comment']))?'has-error':'has-success'?>">
                <label class="control-label" for="inputSuccess4">Veuiller laisser un commentaire sur cet article *</label>
                <textarea name="commentaire" class="form-control" id="inputSuccess4"><?=(isset($_POST['commentaire']))?$_POST['commentaire']:''?></textarea>
                <span class="help-block">* Au moins 10 caractères</span>
              </div>
              <input type="submit" name='submitComment' value="Commenter"><br><br>
            </form>
            <?php endif; ?>
          </section>
        </article>
<?php

  // Arrêter le curseur
  $commentairesPrep->closeCursor();

  // Fermer la connexion
  $pdoConf = null;

else:
  header('Location: /');
endif;

// Footer
include_once './inc/footer.inc.php';
?>