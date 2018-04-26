<?php
//session_start();

  // PDO
  //require_once 'inc/PDOConfig.php';

  // Instance de la base principale
  //$pdoConf = new PDOConfig();


  // Récupère le contenu commentaire en session
  (isset($_POST['commentaire']))?$commentaire = $_POST['commentaire']:$commentaire = '';

  // Insertion
  if (!empty($commentaire)){
    $idMembre = intval($_SESSION['membre']);
    $idArticle = intval($_GET['id']);
    $commentaire = nl2br(strip_tags($commentaire));

    $req = 'INSERT INTO commentaires (id_membre,id_article,commentaire) VALUES (:idMembre,:idArticle,:commentaire)';
    $commentairePrep = $pdoConf->prepare($req);
    $commentairePrep->bindValue(':idMembre', $idMembre );
    $commentairePrep->bindValue(':idArticle', $idArticle );
    $commentairePrep->bindValue(':commentaire', $commentaire);
    if($commentairePrep->execute()){
      $_SESSION['success_comment'] = '<div class="text-success">Votre commentaire est en cours de validation et sera publié dès que possible.</div>';
    }

    // Vider les champs du formulaire de contact
    unset($_POST['commentaire']);
    unset($_POST['submitComment']);

  }

 // header("Location: article.php?id=" . $_GET['id'] );

