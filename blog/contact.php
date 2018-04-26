<?php
include_once './inc/header.inc.php';
include_once './inc/blog.inc.php';
$errors = contact();
?>
      
        <div class="misc">
          <h1>Contactez-moi ! </h1>
          <div class="jumbotron--bis curve">
            <form method="post" action="<?=$_SERVER['PHP_SELF']?>">
              <div class="form-group <?=(isset($errors['lastname']))?'has-error':'has-success'?>">
                <label class="control-label" for="inputSuccess1">Votre nom *</label>
                <input type="text" name="lastname" class="form-control" id="inputSuccess1" value="<?=(isset($_POST['lastname']))?$_POST['lastname']:''?>">
                <span class="help-block">* Votre nom (obligatoire)</span>
              </div>
              
              <div class="form-group <?=(isset($errors['email']))?'has-error':'has-success'?>">
                <label class="control-label" for="inputSuccess2">Votre adresse email *</label>
                <input type="text" name="email" class="form-control" id="inputSuccess2" value="<?=(isset($_POST['email']))?$_POST['email']:''?>">
                <span class="help-block">* Votre email (obligatoire)</span>
              </div>
              
              <div class="form-group <?=(isset($errors['subject']))?'has-error':'has-success'?>">
                <label class="control-label" for="inputSuccess4">Sujet de votre message ? *</label>
                <input type="text" name="subject" class="form-control" id="inputSuccess3" value="<?=(isset($_POST['subject']))?$_POST['subject']:''?>">
                <span class="help-block">* Titre de votre message (obligatoire)</span>
              </div>
              <div class="form-group <?=(isset($errors['message']))?'has-error':'has-success'?>">
                <label class="control-label" for="inputSuccess4">En quoi puis-je vous aider ? *</label>
                <textarea name="message" class="form-control" id="inputSuccess4"><?=(isset($_POST['message']))?$_POST['message']:''?></textarea>
                <span class="help-block">* Votre message (obligatoire)</span>
              </div>
              
              <input class="btn btn-default" name="valid" type="submit" value="Envoyer!">
            </form>
            <div class="clearfix"></div>
           </div>
        </div>

<?php

// Footer
include_once './inc/footer.inc.php';
?>