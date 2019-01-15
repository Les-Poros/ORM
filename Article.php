<?php

class Article extends \Mod\Model {

    public $table = 'Article';
    public $primaryKey = 'id';

    public function __construct($tab = ['id' => null, 'nom'=> null, 'descr' => null, 'tarif' => null, 'id_categ' => null]) {
        parent::__construct($tab);
    }

    public function categorie(){
        return $this->belongs_to("Categorie","id_categ");
    }
}