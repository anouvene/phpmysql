<?php
include_once ('../inc/header.inc.php');

if (isset($_SESSION['membre']) && checkPermissions($_SESSION['membre'], 'edit_posts') && isset($_GET['id']) && !empty($_GET['id'])):
    extract($_SESSION);
    $article = article ($_GET['id']);
    $errors = modifierArticle($_GET['id']);

    if(!empty($article['image'])){
        $_SESSION['image'] = $article['image'];
    }
?>
    <div class="misc">
        <h1>Modifier un article</h1>
        <div class="jumbotron--bis curve">
            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="updateArticle">
                <div class="form-group <?=(isset($errors['titre']))?'has-error':'has-success'?>">
                    <label class="control-label" for="inputSuccess1">Libéllé de l'article *</label>
                    <input type="text" class="form-control" id="inputSuccess1" name="titre" value="<?=(isset($_POST['titre']))?$_POST['titre']:$article['titre']?>">
                    <span class="help-block">* Libéllé de l'article (obligatoire)</span>
                </div>
                <div class="form-group <?=(isset($errors['accroche']))?'has-error':'has-success'?>">
                    <label class="control-label" for="inputSuccess2">Extrait de l'article *</label>
                    <textarea name="accroche" class="form-control" id="inputSuccess2"><?=(isset($_POST['accroche']))?$_POST['accroche']:$article['accroche']?></textarea>
                    <span class="help-block">Extrait de l'article (obligatoire)</span>
                </div>
                <div class="form-group <?=(isset($errors['contenu']))?'has-error':'has-success'?>">
                    <label class="control-label" for="inputSuccess3">Description complète de l'article *</label>
                    <textarea name="contenu" class="form-control contenu-post" id="inputSuccess3"><?=(isset($_POST['contenu']))?$_POST['contenu']:$article['contenu']?></textarea>
                    <span class="help-block">Corps de l'article (obligatoire)</span>
                </div>
                <div class="form-group <?=(isset($errors['imgFile']))?'has-error':'has-success'?>">
                    <label class="control-label" for="inputSuccess4">Photo article *</label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="20000000">
                    <img class="img-thumbnail" src="../img/img_bis/<?=$article['image']?>" alt="" style="width: 50px; height: 50px">
                    <button id="capture" class="btn btn-info">Changer l'image ?</button>
                    <input type="file" name="imgFile" class="form-control" id="upload" style="display: none;">
                    <span class="help-block">photo de l'article (obligatoire)</span>
                </div>
                <input class="btn btn-default" name="valid" type="submit" value="Valider la modification de cet article">
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
<?php
else :
    header('Location: /admin/');
endif;

// Footer
include_once (realpath(__DIR__. '/../inc/footer.inc.php'));
?>