<?php
session_start();
require_once './inc/blog.inc.php';

if($_SESSION['membre']) {
    if (checkPermissions($_SESSION['membre'], 'edit_posts')) {
        if (/*isset($_POST['id_membre']) &&*/ isset($_POST['id_comment']) && isset($_POST['publie'])) {
            $commentairePrep = $pdoConf->prepare('UPDATE commentaires SET publie = :publie WHERE id = :id_comment');
            $commentairePrep->bindParam('publie', $_POST['publie']);
            //$commentairePrep->bindParam('id_membre', $_POST['id_membre']);
            $commentairePrep->bindParam('id_comment', $_POST['id_comment']);
            if($commentairePrep->execute()) {
                echo 'm'.$_POST['id_membre'].'c'.$_POST['id_comment'].'_'.$_POST['publie'];
            }
        }
    } else {
        header('Location: inscriptionUpdate.php');
    }
} else {
    header('Location: ./../inscription.php');
}