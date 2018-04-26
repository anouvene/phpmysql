<?php
    include_once ('../inc/header.inc.php');
    if($_SESSION['membre']):
?>

      <?php
      if (checkPermissions($_SESSION['membre'], 'edit_posts')) :
        $articles = articles();
        if($articles->rowCount() > 0):
      ?>
      <div class="misc">
        <h1>Anciens posts !</h1>
          <table class="table-hover">
            <?php while($article = $articles->fetch()): ?>
            <tr>
              <td><?=$article['titre']?></td>
              <td><a href="modifierPost.php?id=<?=$article['id']?>"  class="ico-update"></a></td>
              <td><a href="supprimerPost.php?id=<?=$article['id']?>" class="ico-delete"></a></td>
            </tr>
            <?php endwhile; ?>
          </table>
      </div>
      <?php
        endif;
      endif;
      ?>

<?php
    else:
        header('Location: ./../inscription.php');
    endif;

    include_once (realpath(__DIR__. '/../inc/footer.inc.php'));
?>