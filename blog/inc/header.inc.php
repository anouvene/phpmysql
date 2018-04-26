<?php
    session_start();
    include ('blog.inc.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP-MySQL - Accueil</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:400,300,700">
    <!--<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="<?='/../css/main.css'?>">
</head>
<body id="<?=(isset($bodyID))?$bodyID:''?>">
    <div class="page-wrapper">
      <header>
        <div class="header__content">
          <div class="logo"><a href="./">PHP/MySQL</a></div>

          <nav>
            <ul>
              <li><a href="./../">Accueil</a></li>
              <li><a href="./../contact.php">Contact</a></li>
            <?php if(isset($_SESSION['membre'])) :?>
              <?php if (checkPermissions($_SESSION['membre'], 'create_post') || checkPermissions($_SESSION['membre'], 'edit_comments') ) :?>
              <li><a href="./../admin/">Admin</a></li>
              <?php endif; ?>
              <li><a href="./../admin/inscriptionUpdate.php">Compte</a></li>
              <?php if (checkPermissions($_SESSION['membre'], 'edit_posts') ) :?>
              <li><a href="./../admin/posts.php">Archives</a></li>
              <?php endif; ?>
              <li><a href="./../admin/deconnexion.php">Déconnexion</a></li>
            <?php else : ?>
              <li><a href="./../connexion.php">Connexion</a></li>
              <li><a href="./../inscription.php">Inscription</a></li>
            <?php endif; ?>
            </ul>
          </nav>

        </div>
      </header>
<?php

$page = basename($_SERVER["REQUEST_URI"], ".php");

if($page != 'admin' && $page != 'inscriptionUpdate' && $page != 'posts') {
  //print_r($page);
  include 'search.inc.php';
}
?>
      <div class="page__content">
