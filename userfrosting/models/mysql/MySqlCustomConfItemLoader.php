<?php

namespace UserFrosting;

class MySqlCustomConfItemLoader extends MySqlObjectLoader {

    protected static $_table;       // The table whose rows this class represents. Must be set in the child concrete class.   

    public static function exists($value, $name = "id") {
        return parent::fetch($value, $name);
    }

    public static function fetch($value, $name = "id") {
        $results = parent::fetch($value, $name);

        if ($results)
            return new MySqlCustomConfItem($results, $results['id']);
        else
            return false;
    }

    public static function fetchAll($value = null, $name = null) {
        $resultArr = parent::fetchAll($value, $name);

        $results = [];
        foreach ($resultArr as $id => $group)
            $results[$id] = new MySqlCustomConfItem($group, $id);

        return $results;
    }

    public static function update($id, $hourstartam, $hourendam, $hourstartpm, $hourendpm) {
        $db = static::connection();

        $table = static::$tables['workinghours']->name;

        $query = "UPDATE `$table`"
                . "SET hourstartam = ?, hourendam = ?,"
                . " hourstartpm = ?, hourendpm = ? "
                . "WHERE id = ? ";

        $stmt = $db->prepare($query);
        $result = $stmt->execute(array($hourstartam, $hourendam, $hourstartpm, $hourendpm, $id));
        return $result;
    }

}
