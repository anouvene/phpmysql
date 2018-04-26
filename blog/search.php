<?php
  /**
   * Search page
   * Rechercher des articles par rapport à leur titre et contenu
   */
  $bodyID = 'homepage';
  include_once './inc/header.inc.php'; // Header

  if (!empty(trim($_POST['query'])) && isset($_POST['query'])) {

    $queryTitre = '%' . trim($_POST['query']) . '%';
    $queryContenu = trim($_POST['query']);

    $articles = $pdoConf->prepare('SELECT id, titre, accroche, publication, image FROM articles WHERE titre LIKE :queryTitre OR MATCH (accroche) AGAINST (:queryContenu IN BOOLEAN MODE) OR MATCH (contenu) AGAINST (:queryContenu IN BOOLEAN MODE)');
    $articles->bindParam(':queryTitre', $queryTitre);
    $articles->bindParam(':queryContenu', $queryContenu);
    $articles->execute();

    $articles->bindColumn('id', $id);
    $articles->bindColumn('titre', $titre);
    $articles->bindColumn('accroche', $accroche);
    $articles->bindColumn('publication', $publication);
    $articles->bindColumn('image', $image);

  while ($articles->fetch(PDO::FETCH_BOUND)) :
?>

  <article>
    <img src="img/img_bis/<?=$image?>" alt="">
    <?= publication($publication,'', '') ?>
    <h1><?=$titre?></h1>
    <p><?=$accroche?></p>
    <a href="article.php?id=<?=$id?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> Lire l'article</a>
  </article>

<?php
  endwhile;

  // Stopper le curseur
  $articles->closeCursor();

  // Fermer la connexion
 // $pdoConf = null;

  // Footer
  include_once './inc/footer.inc.php';
} else {
  header('Location: /');
}
?>