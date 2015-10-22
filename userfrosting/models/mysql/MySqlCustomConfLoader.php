<?php

namespace UserFrosting;

class MySqlCustomConfLoader extends MySqlObjectLoader {

    protected static $_table;       // The table whose rows this class represents. Must be set in the child concrete class.   

    public static function exists($value, $name = "id") {
        return parent::fetch($value, $name);
    }

    public static function fetch($value, $name = "id") {
        $results = parent::fetch($value, $name);

        if ($results)
            return new MySqlCustomConf($results, $results['id']);
        else
            return false;
    }

    public static function fetchAll($value = null, $name = null) {
        $resultArr = parent::fetchAll($value, $name);

        $results = [];
        foreach ($resultArr as $id => $group)
            $results[$id] = new MySqlCustomConf($group, $id);

        return $results;
    }

    public static function fetchAllExceptStandard() {
        $db = static::connection();
        $table = static::$tables['custom_conf']->name;
        $query = "
            SELECT *
            FROM `$table`
            WHERE `$table`.id NOT IN (1,2)";

        $stmt = $db->prepare($query);

        $stmt->execute();

        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $results[$id] = new MySqlCustomConf($row, $row['id']);
        }
        return $results;
    }

}
