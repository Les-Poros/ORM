<?php

require_once('./ConnectionFactory.php');
require_once('./Query.php');
require_once('./Model.php');
require_once('./Article.php');
require_once('./Categorie.php');

$conf = parse_ini_file('conf/conf.ini');
\Connection\ConnectionFactory::makeConnection($conf);

$a = new Article() ;
$a->nom = 'A12609' ;
$a->descr = 'beau velo de course
rouge' ;
$a->tarif = 59.95;
$a->id_categ = 1;

$a->insert();