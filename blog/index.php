<?php
$bodyID = 'homepage';
include_once './inc/header.inc.php'; // Header
include_once './inc/blog.inc.php'; // functions

$articles = articles();

while ($article = $articles->fetch()) :
?>

  <article>
    <img src="img/img_bis/<?=$article['image']?>" alt="">
    <?= publication($article['publication'],'', '') ?>
    <h1><?=$article['titre']?></h1>
    <p><?=$article['accroche']?></p>
    <a href="article.php?id=<?=$article['id']?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Lire l'article</a>
  </article>

<?php
endwhile;
// Stopper le curseur
$articles->closeCursor();

// Fermer la connexion
$pdoConf = null;

// Footer
include_once './inc/footer.inc.php';
?>
