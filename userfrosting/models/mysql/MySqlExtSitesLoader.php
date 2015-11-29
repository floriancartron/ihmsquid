<?php

namespace UserFrosting;

class MySqlExtSitesLoader extends MySqlObjectLoader {

    protected static $_table;       

    public static function exists($value, $name = "id") {
        return parent::fetch($value, $name);
    }


    public static function fetch($value, $name = "id") {
        $results = parent::fetch($value, $name);

        if ($results)
            return new MySqlExtSites($results, $results['id']);
        else
            return false;
    }


    public static function fetchAll($value = null, $name = null) {
        $resultArr = parent::fetchAll($value, $name);

        $results = [];
        foreach ($resultArr as $id => $group)
            $results[$id] = new MySqlExtSites($group, $id);

        return $results;
    }
    
    public static function updateConf($conf){
        $db = static::connection();
        
        $query="UPDATE uf_configuration "
                . "SET value = ? "
                . "WHERE name = 'ext_sites'";
                $stmt = $db->prepare($query);
        $result = $stmt->execute(array($conf));
        return $result;
    }
}