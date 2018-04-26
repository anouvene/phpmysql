<?php
/**
 *  ARTICLES
 * En MySQL, la valeur du timestamp est en datetime
 * Voici la requete MySQL pourtransformer un datetime en date
 * SELECT colonne_timestamp,
 * FROM_UNIXTIME(colonne_timestamp [,'%Y %D %M %h:%i:%s']) as valeur_datetime,
 * CAST(FROM_UNIXTIME(colonne_timestamp) as date) as valeur_date
 * FROM table
 *
 * http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html#function_date-format
 */

/*
 * Exemple :
 *
 * colonne_timestamp : 1347811456
 * valeur_datetime : 2012-09-16 18:04:16
 * valeur_date : 2012-09-16
 *
 */






$date = date('Y-m-d');
$heure= date('H:m');

// Date format français
$dateExplode = explode('-', $date);
$annee = $dateExplode[0];
$mois = $dateExplode[1];
$jour = $dateExplode[2];

switch($mois){
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
}


$articles = [
  ["id"=>0, "img"=>"https://placeimg.com/640/480/people", "date"=>$date . " " . $heure, "titre"=>"People", "desc"=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit similique eum sequi! Culpa quam earum, iusto atque incidunt porro ad quae sint, doloremque molestiae qui recusandae repudiandae sequi eius eos."],
  ["id"=>1, "img"=>"https://placeimg.com/640/480/people", "date"=>$date . " " . $heure, "titre"=>"People", "desc"=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit similique eum sequi! Culpa quam earum, iusto atque incidunt porro ad quae sint, doloremque molestiae qui recusandae repudiandae sequi eius eos."],
  ["id"=>2, "img"=>"https://placeimg.com/640/480/people", "date"=>$date . " " . $heure, "titre"=>"People", "desc"=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit similique eum sequi! Culpa quam earum, iusto atque incidunt porro ad quae sint, doloremque molestiae qui recusandae repudiandae sequi eius eos."],
  ["id"=>3, "img"=>"https://placeimg.com/640/480/people", "date"=>$date . " " . $heure, "titre"=>"People", "desc"=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit similique eum sequi! Culpa quam earum, iusto atque incidunt porro ad quae sint, doloremque molestiae qui recusandae repudiandae sequi eius eos."],
  ["id"=>4, "img"=>"https://placeimg.com/640/480/people", "date"=>$date . " " . $heure, "titre"=>"People", "desc"=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit similique eum sequi! Culpa quam earum, iusto atque incidunt porro ad quae sint, doloremque molestiae qui recusandae repudiandae sequi eius eos."],
  ["id"=>5, "img"=>"https://placeimg.com/640/480/people", "date"=>$date . " " . $heure, "titre"=>"People", "desc"=>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugit similique eum sequi! Culpa quam earum, iusto atque incidunt porro ad quae sint, doloremque molestiae qui recusandae repudiandae sequi eius eos."],
];