<?php
/**
 * PDO
 * Dans certains cas, on aura besoin de travailler avec des bases de données différentes
 * Pour cette raison, on fournit un argument au constructeur
 * A cet argument, on lui attribue une valeur par défaut :
 * un fichier.ini contenant les paramètres de connexion pour la base principale
 */

class PDOConfig extends PDO {
  // Instance PDO
  public function __construct($file = 'bdd_settings.ini') {
    // Erreur de lecture du fichier de config "bdd.settings.ini"
    if (!$settings = parse_ini_file($file, TRUE))
      throw new exception('Impossible d\'ouvrir ' . $file . '.');

    // $dns = 'mysql:host=localhost;port=3306;'
    // Attention pas d'espace avant et après le signe =
    $dns  = $settings['database']['driver'];//mysql
    $dns .= ':dbname=' . $settings['database']['dbname'];//blog
    $dns .= ';charset=' . $settings['database']['charset'];//utf8
    $dns .= ';host=' . $settings['database']['host'];//localhost
    $dns .= ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '');//3306

    try {
      parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
      // Configurer certains comportements de PDO grâce à la méthode setattribute()
      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // activation des erreurs
      $this->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING); // variables vides comme NULL
      $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Tableau associatif + voir (FETCH_OBJ)
      $this->setAttribute(PDO::ATTR_CASE , PDO::CASE_LOWER); // change la casse des colonnes dans le résultat
    } catch (PDOException $e) {
      echo 'Erreur de connexion à la base de données <br>' . $e->getMessage() .'<br>';
    }
  }
}



