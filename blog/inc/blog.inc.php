<?php
/**
 * Fonctions utiles pour les blog
 */

// PDO
require_once 'PDOConfig.php';
// Instance de la base principale
$pdoConf = new PDOConfig();


/* Page accueil : Récupération des articles de la base
----------------------------------------*/

function articles () {
  global $pdoConf;


  // Requête simple
  $articles = $pdoConf->prepare('SELECT id, titre, accroche, publication, image FROM articles ORDER BY id DESC');
  $articles->execute();

  return $articles;
}


/* Récupération et traitement de la date de publication des articles
---------------------------------------------------------------------*/
function publication ($dateTime, $pseudo, $titre) {
  // datetime format : 2016-08-30 17:16:46
  $lesMois = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
  $dateTimeExplode = explode(' ', $dateTime);
  
  // Heure format : 21h30
  $heureExplode = explode(':', $dateTimeExplode[1]);
  $heure = $heureExplode[0] . 'h';
  $minute = $heureExplode[1];
  $heure =  $heure . $minute;


  // Date format français
  $date = $dateTimeExplode[0];
  $dateExplode = explode('-', $date);

  $annee = $dateExplode[0];
  /*$moisExplode = explode('0', $dateExplode[1]);
  $mois = $moisExplode[1];*/
  $mois = (int)$dateExplode[1];

  $jour = $dateExplode[2];


  /*switch($mois){
    case '01' :
      $mois = 'Janvier';
      break;
    case '02' :
      $mois = 'Février';
      break;
    case '03' :
      $mois = 'Mars';
      break;
    case '04' :
      $mois = 'Avril';
      break;
    case '05' :
      $mois = 'Mai';
      break;
    case '06' :
      $mois = 'Juin';
      break;
    case '07' :
      $mois = 'Juillet';
      break;
    case '08' :
      $mois = 'Août';
      break;
    case '09' :
      $mois = 'Septembre';
      break;
    case '10' :
      $mois = 'Octobre';
      break;
    case '11' :
      $mois = 'Novembre';
      break;
    case '12' :
      $mois = 'Décembre';
      break;
    default:
      break;
  }*/
  if (!empty($pseudo)) {
    $publication = '<p class="date">Posté par ' . strtoupper($pseudo) . ' le <time datetime="' . $date . ' ' . $heure . '">' . $jour . ' ' . lcfirst($lesMois[$mois]) . ' ' . $annee . ' à ' . $heure . '</time></p>';
  } elseif(isset($_SESSION['membre']) && !empty($titre)) {
    $publication = '<p class="date">' . $titre . ' - commenté le <time datetime="' . $date . ' ' . $heure . '">' . $jour . ' ' . lcfirst($lesMois[$mois]) . ' ' . $annee . ' à ' . $heure . '</time></p>';
  } else {
    $publication = '<p class="date">Posté le <time datetime="' . $date . ' ' . $heure . '">' . $jour . ' ' . lcfirst($lesMois[$mois]) . ' ' . $annee . ' à ' . $heure . '</time></p>';
  }

  return $publication ;
}


/* Fiche article
-----------------*/

// Infos article
function article ($id) {

  global $pdoConf;
  //$articleContent = [];

  $articlePrep = $pdoConf->prepare('SELECT titre, accroche, contenu, publication, image FROM articles WHERE id=:id');

  /* Liaison des paramètres
  --------------------------*/
  $articlePrep->bindValue(':id', $id, PDO::PARAM_INT);
  $articlePrep->execute();

  if ($articlePrep->rowCount() > 0) {
    /* Liaison des résultats à des variables
    ---------------------------------------*/
    $articlePrep->bindColumn('titre', $titre);
    $articlePrep->bindColumn('accroche', $accroche);
    $articlePrep->bindColumn('contenu', $contenu);
    $articlePrep->bindColumn('publication', $publication);
    $articlePrep->bindColumn('image', $image);

    /* Récupération des données
    ----------------------------*/
    $articlePrep->fetch(PDO::FETCH_BOUND);

    $articleContent['titre'] = $titre;
    $articleContent['accroche'] = $accroche;
    $articleContent['contenu'] = $contenu;
    $articleContent['publication'] = publication($publication, '', '');
    $articleContent['image'] = $image;

  } elseif (isset($_SESSION['membre']) && checkPermissions($_SESSION['membre'], 'edit_posts')) {
    header('Location: ../admin/posts');
  } else {
    header('Location: /');
  }

  //return $articleContent;
  return $articleContent;
}


// Commentaires

function commentaires () {
  global $pdoConf;

  if (isset($_GET['id']) || isset($GLOBALS['p']) ) {
    if (isset($_GET['id'])) // commentaire(s) membres page article
      $req = 'SELECT pseudo, commentaire, publication FROM commentaires AS c JOIN membres AS m ON c.id_membre=m.id WHERE id_article=:id AND c.publie = :published ORDER BY publication DESC';
    elseif (isset($_SESSION['membre'])) // commentaire(s) du membre dans page compte
      $req = 'SELECT titre, commentaire, c.publication FROM commentaires AS c JOIN membres AS m ON c.id_membre=m.id JOIN articles AS a ON c.id_article=a.id WHERE c.id_membre=:id AND c.publie = :published ORDER BY publication DESC';

    $commentairesPrep = $pdoConf->prepare($req);

    // Liaison des paramètres
    $published = 'oui';
    $id = isset($_GET['id'])?intval($_GET['id']):intval($_SESSION['membre']);
    $commentairesPrep->bindValue(':id', $id, PDO::PARAM_INT);
    $commentairesPrep->bindValue(':published', $published, PDO::PARAM_STR);

  } elseif (checkPermissions($_SESSION['membre'], 'edit_comments')){// pour la gestion des commentaire(s) de tous les membres en page admin
    $req = 'SELECT c.id, id_membre, pseudo, commentaire, publication, publie FROM commentaires AS c JOIN membres AS m ON c.id_membre=m.id ORDER BY publication DESC';
    $commentairesPrep = $pdoConf->prepare($req);
  }
  $commentairesPrep->execute();

  return $commentairesPrep;
}

// Ajouter un commentaire
function verifComment() {
  global $pdoConf;
  $msg = [];

  if (!empty($_POST) && isset($_POST)) {
    extract($_POST);
    $commentaire = strip_tags(trim($commentaire));

    if (empty($commentaire) || strlen($commentaire) < 2 || strlen($commentaire) > 2000) {
      $msg['error_comment'] = '<div class="text-danger">Erreur de saisie, veuiller recommencer.</div>';
      if(isset($_SESSION['success_comment'])){
        unset($_SESSION['success_comment']);
        $_SESSION['success_comment'] = '';
      }
    } /*else{
      $_SESSION['commentaire'] = $commentaire; // transmettre commentaire posté au fichier de traitement 'ajouterCommentaire.php'
      header("Location: ../ajouterCommentaire.php?id=" . $_GET['id'] );
    }*/
  }

  return $msg;
}


// Contact
function contact (){
  $errors = [];
  $showErrors = FALSE;

  if (!empty($_POST) && isset($_POST)) { // On commence par vérifier que le formulaire est bien soumis(champs non vides + remplis)

    foreach ($_POST as $key => $value) { // Nettoyage des données
      $_POST[$key] = trim(strip_tags($value)); // récupération du _POST dans un tableau
    }

    extract($_POST); // posts champs to variables

    // On démarre les vérifications champs par champs

    if (strlen($lastname) < 2 || strlen($lastname) > 50) { // on défini les propriétés de 'lasttname'
      $errors['lastname'] = '<div class="txt-orange text-center" role="alert">Votre nom doit avoir au moins 2 à 50 caractères</div>';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { // on vérifie la syntaxe de "email"
      $errors['email'] = '<div class="txt-orange text-center" role="alert">Votre email n\'est pas valide</div>';
    }

    if (strlen($subject) < 2 || strlen($subject) > 50) { // on vérifie le sujet
      $errors['subject'] = '<div class="txt-orange text-center" role="alert">Le sujet de votre message doit comporter au moins 2 caractères</div>';
    }

    if (strlen($message) < 20 || strlen($message) > 1000) { // on vérifie le message
      $errors['message'] = '<div class="txt-orange text-center" role="alert">Votre message avoir au moins 20 à 500 caractères</div>';
    }

    if (count($errors) > 0) {  // On compte les erreurs, si il y en a (supérieur à 0), on passera la variable $showErrors à true.
      $showErrors = TRUE; // valeur booleen // permettra d'afficher nos erreurs s'il y en a
    } elseif (isset($_POST['valid'])) {

      $destinataire = 'nouvene@gmail.com';
      $sender = $email;
      $subject = $subject;
      $message = '<h1>' . $message .'</h1>';
      $headers  = 'MIME-Version: 1.0' . '\r\n';
      $headers .= 'Content-type: text/html; charset=utf-8' . '\r\n';
      $headers .= 'From : ' . $sender . '\r\n';
      mail($destinataire, $sender, $subject, $message, $headers);

      echo '<div class="text-info text-center" role="contentinfo">Bonjour, ' . $lastname . '. Merci pour votre message, je vous répondrai dans les plus brefs délais.</div>';

      //unset($_POST); // Vider les champs du formulaire de contact

      unset($_POST['lastname']); // Vider les champs du formulaire de contact
      unset($_POST['email']);
      unset($_POST['subject']);
      unset($_POST['message']);
      unset($_POST['valid']);
    }
  }

  if ($showErrors) {
    echo implode('', $errors);
  }

  return $errors;
}



/**
 * Connexion
 * Traitement formulaire de connexion
 */
function connexion () {
  global $pdoConf;
  // Vérifier les valeurs des champs postés un à un
  $errors = [];
  $showErrors = FALSE;

  if (!empty($_POST) && isset($_POST)) {
    // nettoyer les données postées
    foreach ($_POST as $key=>$value) {
      $_POST[$key] = trim(strip_tags($value));
    }

    if ( strlen($_POST['nickname'])<2 || strlen($_POST['nickname'])>50 ) { // nickname
      $errors['nickname'] = '<div class="txt-orange text-center" role="alert">Votre pseudo n\'est pas valide !</div>';
    }

    if ( strlen($_POST['password'])<6 || strlen($_POST['password'])>50 ) { // password
      $errors['password'] = '<div class="txt-orange text-center" role="alert">Votre mot de passe n\'est pas valide !</div>';
    }

    if (count($errors) > 0) {  // On compte les erreurs, si il y en a (supérieur à 0), on passera la variable $showErrors à true.
      $showErrors = TRUE; // permettra d'afficher nos erreurs s'il y en a
    }

    // Affichage des erreurs
    if ($showErrors) {
      echo implode('', $errors);
    } else { // Sinon, vérification pseudo et mot de passe
      // Extraire les champs postés en variables
      extract($_POST);

      // Se connecter à la base et extraire les champs 'pseudo' et 'password' et les comparer avec $nickname et $password
      //$pdoConf = new PDOConfig();
      $connexion = $pdoConf->prepare('SELECT id, pseudo, email, password FROM membres WHERE pseudo = CONVERT (:nickname USING utf8) COLLATE utf8_bin');

      // Lier parametre à la requete
      $connexion->bindValue(':nickname', $nickname);
      $connexion->execute();

      // Récupérer les données de la requete
      $connexion->bindColumn('id', $idBis);
      $connexion->bindColumn('pseudo', $nicknameBis);
      $connexion->bindColumn('email', $emailBis);
      $connexion->bindColumn('password', $passwordBis);

      $connexion->fetch(PDO::FETCH_BOUND);

      if (password_verify($password, $passwordBis)) {
        // Si ok, établir une session utilisateur et redirection vers page admin/index.php
        $_SESSION['membre'] = $idBis;
        $_SESSION['nickname'] = $nicknameBis;
        $_SESSION['email'] = $emailBis;
        header('Location: admin/index.php');
      } else {
        echo '<div class="txt-orange text-center" role="alert">Vous ne disposez aucun compte client chez nous.</div>';
        echo '<div class="txt-orange text-center" role="alert">Merci de bien vouloir vous inscrire => <a href="inscription.php" title="Inscription">ICI</a></div>';
      }
    }
  }

  return $errors;
}


// Inscription
function inscription () {
  global $pdoConf;
  $invalid = FALSE;
  $errors = [];

  if (!empty($_POST) && isset($_POST)) {
    // Nettoyer les données postées
    foreach ($_POST as $key=>$value) {
      $_POST[$key] = trim(strip_tags($value));
    }

    // Convertir posts en variables
    extract($_POST);

    // Vérifier la syntaxe des champs
    if ( count($errors = verifChamps ($_POST)) > 0 ) {
      $invalid = TRUE;
      //$errors = verifChamps($_POST);
    }

    // Affichage des erreurs
    if ($invalid) {
      echo implode('', $errors);
    } else { // Sinon, insertion des données dans la tables membres

      $query = 'INSERT INTO membres (pseudo, email, password) VALUES (:nickname, :email, :password)';
      insertChamps($_POST, $query);
    }
  }


  return $errors;
}

// Vérifier si une valeur saisie existe déjà dans une table

function existDeja($table, $attrib, $attribValue) {
  global $pdoConf;

  $q = "SELECT COUNT(*) FROM $table WHERE $attrib = convert(:attribValue USING utf8) COLLATE utf8_bin";
  $pseudoPrep = $pdoConf->prepare($q);
  $pseudoPrep->bindValue('attribValue', $attribValue);
  $pseudoPrep->execute();

  if( (int)$pseudoPrep->fetch() == 0){
    return FALSE;
  }
  return TRUE;
}

// Vérifier les syntaxes du pseudo, email et mot de passe

function verifChamps ($posts) {
  extract($posts);

  $errors = [];

  // Verif pseudo
  if ( strlen($nickname)<2 || strlen($nickname)>50 ) { // nickname
    $errors['nickname'] = '<div class="txt-orange text-center" role="alert">Votre pseudo n\'est pas valide !</div>';
  } elseif ( existDeja('membres', 'pseudo', $nickname) ) {// Si pseudo existe dans la base
    if(isset($_SESSION['membre']) && strnatcmp($_SESSION['nickname'], $nickname) == 0 ){
      ;
    } else {
      $errors['nickname'] = '<div class="txt-orange text-center" role="alert">Le pseudo ' . $nickname . ' est déjà pris, veuiller choisir un autre</div>';
    }
  }

  // Verif email & emailconf
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { // on vérifie la syntaxe de "email"
    $errors['email'] = '<div class="txt-orange text-center" role="alert">Votre email n\'est pas valide</div>';
  } elseif ( existDeja('membres', 'email', $email) ){
    if(isset($_SESSION['membre']) && strnatcmp($_SESSION['email'], $email) == 0){
      ;
    } else {
      $errors['email'] = '<div class="txt-orange text-center" role="alert">Cet email existe déjà, veuiller saisir un autre</div>';
    }
  } elseif ($emailconf !== $email){
    $errors['emailconf'] = '<div class="txt-orange text-center" role="alert">Votre email de confirmation est différent</div>';
  }

  // Verif password & passwordconf
  if ( isset($_SESSION['membre']) && empty($password) ){
    ;
  } else {
    if ( strlen($password)<6 || strlen($password)>100 ) { // password
      $errors['password'] = '<div class="txt-orange text-center" role="alert">Votre mot de passe n\'est pas valide !</div>';
    } elseif ($passwordconf !== $password) {
      $errors['passwordconf'] = '<div class="txt-orange text-center" role="alert">Votre mot de passe de confirmation n\'est pas valide, car différent</div>';
    }
  }

  return $errors;
}


// Insert champs
function insertChamps ($posts, $q) {
  global $pdoConf;

  extract($posts);

  $membrePrep = $pdoConf->prepare($q);
  $membrePrep->bindValue(':nickname', $nickname);
  $membrePrep->bindValue(':email', $email);
  $membrePrep->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
  $membrePrep->execute();

  // Etablir une session utilisateur
  $_SESSION['membre'] = $pdoConf->lastInsertId();
  $_SESSION['nickname'] = $nickname;
  $_SESSION['email'] = $email;

  // Vider les champs
  unset($_POST);

  /*unset($_POST['pseudo']);// vider les champs avant de quitter la page connexion
  unset($_POST['email']);
  unset($_POST['emailconf']);
  unset($_POST['password']);
  unset($_POST['passwordconf']);*/

  // Quitter la page inscription pour aller dans accueil admin
  header('Location: admin/');
}


// Update champs
function updateChamps ($posts, $q, $id) {
  global $pdoConf;

  extract($posts);

  $membrePrep = $pdoConf->prepare($q);
  $membrePrep->bindValue(':id', $id);
  $membrePrep->bindValue(':nickname', $nickname);
  $membrePrep->bindValue(':email', $email);
  if(!empty($password)){
    $password = password_hash($password, PASSWORD_DEFAULT);
    $membrePrep->bindValue(':password', $password);
  }

  $membrePrep->execute();
}


// Mise à jour coordonnées du membre connecté
function inscriptionUpdate ($idMembre) {
  global $pdoConf;

  $invalid = FALSE;
  $errors = [];

  if (!empty($_POST) && isset($_POST)) { // Update champs
    // Nettoyer les données postées
    foreach ($_POST as $key => $value) {
      $_POST[$key] = trim(strip_tags($value));
    }

    // Convertir posts en variables
    extract($_POST);

    // Vérifier la syntaxe des champs
    if (count($errors = verifChamps($_POST)) > 0) {
      $invalid = TRUE;
    }

    // Affichage des erreurs
    if ($invalid) {
      echo implode('', $errors);
    }
    else { // Sinon, mise à jour des données dans la tables membres
      if ( isset($_SESSION['membre']) && !empty($password) ){// + modif password
        $query = 'UPDATE membres SET pseudo = :nickname, email = :email, password = :password WHERE id = :id';
      } else {
        $query = 'UPDATE membres SET pseudo = :nickname, email = :email WHERE id = :id';
      }

      updateChamps($_POST, $query, $idMembre);

      // Quitter la page inscription pour aller dans accueil admin
      header('Location: ../admin/');
    }
  }
  return $errors;
}

// Compte : Afficher les infos du membre

function afficheMembre ($idMembre) {
  global $pdoConf;

  // Affichage champs
  $membrePrep = $pdoConf->prepare('SELECT * FROM membres WHERE id = :idMembre');
  $membrePrep->bindValue(':idMembre', $idMembre);
  $membrePrep->execute();

  return $membrePrep->fetch();
}

// Permission d'effectuer ou non une tâche précise pour un membre
function checkPermissions($idMembre, $tache) {
  global $pdoConf;

  $idMembre = intval($idMembre);
  $taches = array('edit_membres', 'create_post', 'edit_posts', 'create_comment', 'edit_comments');// Tâches pré-définies par défaut
  $permissions = array(0, 0, 0, 1, 0); // Permissions correspondantes par défaut (pour tous les membres)

  if(in_array($tache, $taches)) {

    $sql_check_perms = $pdoConf->prepare("SELECT $tache FROM module_permissions WHERE id_membre = :idMembre");

    $sql_check_perms->bindValue(':idMembre', $idMembre);
    $sql_check_perms->execute();

    $nbre = $sql_check_perms->rowCount(); // nb de résultat

    if($nbre == 0) { // Si le membre n'ayant aucune tâche dans la table 'module_permissions', on lui attribue celles par défaut + permissions correspodantes (Ligne 498)

      $tachesPrep = $pdoConf->prepare('INSERT INTO module_permissions (id_membre, edit_membres, create_post, edit_posts, create_comment, edit_comments) VALUES (:id, :p1, :p2, :p3, :p4, :p5)');
      $tachesPrep->bindValue(':id', $idMembre);
      $tachesPrep->bindValue(':p1', $permissions[0]);
      $tachesPrep->bindValue(':p2', $permissions[1]);
      $tachesPrep->bindValue(':p3', $permissions[2]);
      $tachesPrep->bindValue(':p4', $permissions[3]);
      $tachesPrep->bindValue(':p5', $permissions[4]);

      if($tachesPrep->execute()) { // insertion réussie
        if($permissions[array_search($tache, $taches)] == 1) { // permission tâche accordée
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    } else { // pour un membre ayant déjà une tâche, on vérifie la permission correspondante dans la table 'module_permissions'
      
      $data_perms = $sql_check_perms->fetch();

      if($data_perms[$tache] == 1) {
        return true;
      } else {
        return false;
      }
    }
    // Fermer la connexion
    $pdoConf = null;
  } else {
    return false;
  }
}

// Ajout post

function ajouterArticle() {
  global $pdoConf;

  $invalid = FALSE;
  $errors = [];

  if (!empty($_POST) && isset($_POST)) { // Formulaire posté & champs non vides

    // Nettoyer les données postées
    foreach ($_POST as $key => $value) {
      $_POST[$key] = trim(strip_tags($value));
    }

    // Convertir posts en variables
    extract($_POST);

    // Titre
    if (empty($titre)) {
      $errors['titre'] = '<div class="txt-orange text-center" role="alert">Titre non valide !</div>';
    }

    // Extrait
    if (strlen($accroche) < 2 || strlen($accroche) > 200) { // on vérifie le message
      $errors['accroche'] = '<div class="txt-orange text-center" role="alert">L\'extrait doit contenir entre 10 à 200 caractères</div>';
    }

    // Contenu
    if (empty($contenu) || strlen($contenu) < 2) { // on vérifie le message
      $errors['contenu'] = '<div class="txt-orange text-center" role="alert">Le contenu doit avoir au moins 10 caractères</div>';
    }

    // Image file
    if ($_FILES['imgFile']['error']) {
     switch ($_FILES['imgFile']['error']) {
       case 1: // UPLOAD_ERR_INI_SIZE
         $errors['imgFile'] = '<div class="txt-orange text-center" role="alert">Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !</div>';
         break;
       case 2: // UPLOAD_ERR_FORM_SIZE
         $errors['imgFile'] = '<div class="txt-orange text-center" role="alert">Le fichier dépasse la limite autorisée dans le formulaire HTML !</div>';
         break;
       case 3: // UPLOAD_ERR_PARTIAL
         $errors['imgFile'] = '<div class="txt-orange text-center" role="alert">L\'envoi du fichier a été interrompu pendant le transfert !</div>';
         break;
       case 4: // UPLOAD_ERR_NO_FILE
         $errors['imgFile'] = '<div class="txt-orange text-center" role="alert">Le fichier que vous avez envoyé a une taille nulle !</div>';
         break;
     }
    }

    // Vérifier la syntaxe des champs
    if (count($errors) > 0) {
      $invalid = TRUE;
    }

    if ($invalid) {
      // Affichage des erreurs
      echo '<br><br>' . implode('', $errors);
    } else { // Sinon, mise à jour des données dans la tables membres
      // Upload fichier image
      $imgFileName = $_FILES['imgFile']['tmp_name'];
      $imgFileDestination = '../img/img_bis/' . $_FILES['imgFile']['name'];
      move_uploaded_file($imgFileName, $imgFileDestination);

      $query = 'INSERT INTO articles (titre, accroche, contenu, image) VALUES (:titre, :accroche, :contenu, :image)';

      $articlePrep = $pdoConf->prepare($query);
      $articlePrep->bindValue(':titre', $titre);
      $articlePrep->bindValue(':accroche', $accroche);
      $articlePrep->bindValue(':contenu', $contenu);
      $articlePrep->bindValue(':image', $_FILES['imgFile']['name']);

      if($articlePrep->execute()){
        unset($_SESSION['image']);
        $_SESSION['image'] = null;
        if (checkPermissions($_SESSION['membre'], 'edit_posts'))  {
          // Retour aux archives si membre autorisé
          header('Location: ../admin/posts.php');
        } else {
          // Quitter la page inscription pour aller voir l'article fraichement crée dans la page accueil du site
          header('Location: /');
        }
      }


    }
  }
  return $errors;
}


function modifierArticle($artId) {
  global $pdoConf;

  $invalid = FALSE;
  $errors = [];

  if (!empty($_POST) && isset($_POST)) { // Formulaire posté & champs non vides

    // Nettoyer les données postées
    foreach ($_POST as $key => $value) {
      $_POST[$key] = trim(strip_tags($value));
    }

    // Convertir posts en variables
    extract($_POST);

    // Titre
    if (empty($titre)) {
      $errors['titre'] = '<div class="txt-orange text-center" role="alert">Titre non valide !</div>';
    }

    // Extrait
    if (strlen($accroche) < 2 || strlen($accroche) > 200) { // on vérifie le message
      $errors['accroche'] = '<div class="txt-orange text-center" role="alert">L\'extrait doit contenir entre 10 à 200 caractères</div>';
    }

    // Contenu
    if (empty($contenu) || strlen($contenu) < 2) { // on vérifie le message
      $errors['contenu'] = '<div class="txt-orange text-center" role="alert">Le contenu doit avoir au moins 10 caractères</div>';
    }

    // Image file
    // Pour la mise à jour : Si une image existe déjà et qu'on ne veut pas la modifier
    if(isset($action) && $action == 'updateArticle' && isset($_FILES['imgFile']) && $_FILES['imgFile']['size'] == 0 && isset($_SESSION['image'])){
      ; // on ne fait rien
    } elseif ($_FILES['imgFile']['error']) {
      switch ($_FILES['imgFile']['error']){
        case 1: // UPLOAD_ERR_INI_SIZE
          $errors['imgFile'] = '<div class="txt-orange text-center" role="alert">Le fichier dépasse la limite autorisée par le serveur (fichier php.ini) !</div>';
          break;
        case 2: // UPLOAD_ERR_FORM_SIZE
          $errors['imgFile'] = '<div class="txt-orange text-center" role="alert">Le fichier dépasse la limite autorisée dans le formulaire HTML !</div>';
          break;
        case 3: // UPLOAD_ERR_PARTIAL
          $errors['imgFile'] = '<div class="txt-orange text-center" role="alert">L\'envoi du fichier a été interrompu pendant le transfert !</div>';
          break;
        case 4: // UPLOAD_ERR_NO_FILE
          $errors['imgFile'] = '<div class="txt-orange text-center" role="alert">Le fichier que vous avez envoyé a une taille nulle !</div>';
          break;
      }
    }

    // Vérifier la syntaxe des champs
    if (count($errors) > 0) {
      $invalid = TRUE;
    }

    if ($invalid) {
      // Affichage des erreurs
      echo '<br><br>' . implode('', $errors);
    } else { // Sinon, mise à jour des données dans la tables membres
      // Pour la mise à jour : Si une image existe déjà et qu'on ne veut pas la modifier
      if(isset($action) && $action == 'updateArticle' && isset($_FILES['imgFile']) && $_FILES['imgFile']['size'] == 0 && isset($_SESSION['image'])) {

        $query = 'UPDATE articles SET titre = :titre, accroche = :accroche, contenu = :contenu WHERE id = :artId';
        $articlePrep = $pdoConf->prepare($query);
      } else {
        // Upload fichier image
        $imgFileName = $_FILES['imgFile']['tmp_name'];
        $imgFileDestination = '../img/img_bis/' . $_FILES['imgFile']['name'];
        move_uploaded_file($imgFileName, $imgFileDestination);

        var_dump($_FILES['imgFile']['name']);
        //die();

        $query = 'UPDATE articles SET titre = :titre, accroche = :accroche, contenu = :contenu, image = :image WHERE id = :artId';
        $articlePrep = $pdoConf->prepare($query);
        $articlePrep->bindValue(':image', $_FILES['imgFile']['name']);
      }

      $articlePrep->bindValue(':artId', $artId);
      $articlePrep->bindValue(':titre', $titre);
      $articlePrep->bindValue(':accroche', $accroche);
      $articlePrep->bindValue(':contenu', $contenu);

      if($articlePrep->execute()){
        unset($_SESSION['image']);
        $_SESSION['image'] = null;
        // Retour aux archives si membre autorisé
        header('Location: ../admin/posts.php');
      }


    }
  }
  return $errors;
}


