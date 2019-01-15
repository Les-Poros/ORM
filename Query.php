<?php

namespace Q;

class Query {

    private $sqltable;
    private $fields = '*';
    private $where = "";
    private $args = [];
    private $sql = '';

    public static function table(string $table) {
        $query = new Query;
        $query->sqltable = $table;
        return $query;
    }

    public function where($col, $op, $val) {
        if($this->where!=""){
            $this->where .= " AND ".$col." ".$op." ? ";
        } else {
            $this->where = " WHERE ".$col." ".$op. " ? ";
        
        }
        array_push($this->args, $val);
        return $this;
    }

    public function get() {
        $myPdo = \Connection\ConnectionFactory::getConnection();
        $this->sql = 'select '.$this->fields.' from '.$this->sqltable.$this->where;
        $stmt = $myPdo->prepare($this->sql);
        $stmt->execute($this->args);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function select(array $fields) {
        $this->fields = implode(',', $fields);
        return $this;
    }

    public function delete() {
        $myPdo = \Connection\ConnectionFactory::getConnection();
        $this->sql = 'delete from '.$this->sqltable.$this->where;
        $stmt = $myPdo->prepare($this->sql);
        $stmt->execute($this->args);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert(array $tab) {
        $myPdo = \Connection\ConnectionFactory::getConnection();
        $val = array();
        $i = 0;
        foreach($tab as $t) {
            $val[$i] = $t;
            $i++;
        }
        if($val[0]==null)
        $this->sql='insert into '. $this->sqltable.' values (NULL';
        else
        $this->sql='insert into '. $this->sqltable.' values ('.$val[0];
        for($j=1;$j<sizeof($val);$j++){
            if($val[$j]==null)
            $this->sql.=',NULL';
            else
            $this->sql.=',"'. $val[$j].'"';
        }
        $this->sql.=')';
        $stmt = $myPdo->prepare($this->sql);
        $stmt->execute($this->args);
        return $myPdo->lastInsertId();
    }

}