<?php

namespace UserFrosting;

/* This class is responsible for retrieving Salle object(s) from the database, checking for existence, etc. */

class MySqlWorkingHoursLoader extends MySqlObjectLoader {

    protected static $_table;       // The table whose rows this class represents. Must be set in the child concrete class.   
        
    public static function exists($value, $name = "id"){
        return parent::fetch($value, $name);
    }
   
    public static function fetch($value, $name = "id"){
        $results = parent::fetch($value, $name);
        
        if ($results)
            return new MySqlWorkingHours($results, $results['id']);
        else
            return false;
    }

    public static function fetchAll($value = null, $name = null){
        $resultArr = parent::fetchAll($value, $name);
        
        $results = [];
        foreach ($resultArr as $id => $group)
            $results[$id] = new MySqlWorkingHours($group, $id);

        return $results;
    }
    
    public static function update($id, $hourstartam, $hourendam, $hourstartpm, $hourendpm){
        $db = static::connection();
        
        $table= static::$tables['workinghours']->name;
        
        $sqlVars[":id"] = $id;
        $sqlVars[":hourstartam"] = $hourstartam;
        $sqlVars[":hourendam"] = $hourendam;
        $sqlVars[":hourstartpm"] = $hourstartpm;
        $sqlVars[":hourendpm"] = $hourendpm;
        
        $query= "UPDATE $table"
                . "SET hourstartam = :hourstartam, hourendam = :hourendam,"
                . " hourstartpm = :hourstartpm, hourendpm = :hourendpm"
                . "WHERE id = :id";
        
        $stmt= $db->prepare($query);
        $result = $stmt->execute($sqlVars);
        
        return $result;
    }
}

