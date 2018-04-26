<?php
session_start();
include_once '../inc/blog.inc.php';

if($_SESSION['membre']) {
    if (checkPermissions($_SESSION['membre'], 'edit_posts')) {
        if (isset($_GET['id'])) {
            $articlePrep = $pdoConf->prepare('DELETE FROM articles WHERE id = :artId');
            $articlePrep->bindValue(':artId', $_GET['id'], PDO::PARAM_INT);
            $articlePrep->execute();

            header('Location: posts.php');
        }
    } else {
        header('Location: inscriptionUpdate.php');
    }
} else {
    header('Location: ./../inscription.php');
}
