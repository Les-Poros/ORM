<?php

namespace Mod;

require_once('./Query.php');

abstract class Model {
    
    private $tabArgs = [];

    public function __construct(array $tab) {
        $this->tabArgs = $tab;
    }

    public function __get($property) {
        $keys = array_keys($this->tabArgs);
        for($i=0; $i<sizeof($keys); $i++) {
            if ($keys[$i] == $property) {
                return $this->tabArgs[$keys[$i]];
            }
        }
        return $this->$property();
        throw new \Exception('Propriété invalide !');
    }

    public function __set($property, $value) {
        $keys = array_keys($this->tabArgs);
        $trouve = 'false';
        for($i=0; $i<sizeof($keys); $i++) {
            if ($keys[$i] == $property) {
                $this->tabArgs[$keys[$i]] = $value;
                $trouve = 'true';
            }
        }
        if ($trouve == 'false') {
            throw new \Exception('Propriété ou valeur invalide !');
        }
    }

    public function delete() {
        $q = \Q\Query::table($this->table);
        $q->where($this->primaryKey, '=', $this->tabArgs[$this->primaryKey]);
        $q->delete();
    }

    public function insert() {
        $q = \Q\Query::table($this->table);
        return  $q->insert($this->tabArgs);
    }

    private function getNameOfTable() {
        return static::class;
    }

    public static function all() {
        $classNameSpace = static::class;
        $m=new $classNameSpace();
        $q = \Q\Query::table($m->table)->get();
        $tab=array();

        foreach($q as $ligne){
            array_push($tab,new $classNameSpace($ligne));
        }

        return $tab;
    }

    public static function find(){
        $nbArgs=func_num_args();
        $classNameSpace = static::class;
        $m=new $classNameSpace();
        $q = \Q\Query::table($m->table);
        for($i=0;$i<$nbArgs;$i++){
            if(is_int(func_get_arg($i)))
            $q->where($m->primaryKey,"=",func_get_arg($i));
            else if(isset(func_get_arg($i)[1]) && (func_get_arg($i)[1] == "=" || func_get_arg($i)[1] == "<=" || func_get_arg($i)[1] == ">=" || func_get_arg($i)[1] == "<" || func_get_arg($i)[1] == ">" || func_get_arg($i)[1] == "like"))
            $q->where(func_get_arg($i)[0],func_get_arg($i)[1],func_get_arg($i)[2]);
            else
            $q->select(func_get_arg($i));
        }
        $q=$q->get();
        $tab=array();

        foreach($q as $ligne){
            array_push($tab,new $classNameSpace($ligne));
        }

        return $tab;
    } 

    public static function first(){
        $nbArgs=func_num_args();
        $classNameSpace = static::class;
        $m=new $classNameSpace();
        $q = \Q\Query::table($m->table);
        for($i=0;$i<$nbArgs;$i++){
            if(is_int(func_get_arg($i)))
            $q->where($m->primaryKey,"=",func_get_arg($i));
            else if(isset(func_get_arg($i)[1]) && (func_get_arg($i)[1] == "=" || func_get_arg($i)[1] == "<=" || func_get_arg($i)[1] == ">=" || func_get_arg($i)[1] == "<" || func_get_arg($i)[1] == ">" || func_get_arg($i)[1] == "like"))
            $q->where(func_get_arg($i)[0],func_get_arg($i)[1],func_get_arg($i)[2]);
            else
            $q->select(func_get_arg($i));
        }
        $q=$q->get();
        $tab=array();

        foreach($q as $ligne){
            array_push($tab,new $classNameSpace($ligne));
        }

        return $tab[0];
    }

    public function belongs_to($table,$id_categ){
        return $table::first($this->tabArgs[$id_categ]);
    }

    public function has_many($table,$id_categ){
        return $table::find([$id_categ,"=",$this->tabArgs[$this->primaryKey]]);
    }
}