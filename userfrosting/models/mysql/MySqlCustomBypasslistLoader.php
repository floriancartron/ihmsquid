<?php

namespace UserFrosting;

/* This class is responsible for retrieving Salle object(s) from the database, checking for existence, etc. */

class MySqlCustomBypasslistLoader extends MySqlObjectLoader {

    protected static $_table;       // The table whose rows this class represents. Must be set in the child concrete class.   

    /* Determine if a group exists based on the value of a given column.  Returns true if a match is found, false otherwise.
     * @param value $value The value to find.
     * @param string $name The name of the column to match (defaults to id)
     * @return bool
     */

    public static function exists($value, $name = "id") {
        return parent::fetch($value, $name);
    }

    /* Fetch a single CustomBlacklist based on the value of a given column.  For non-unique columns, it will return the first entry found.  Returns false if no match is found.
     * @param value $value The value to find.
     * @param string $name The name of the column to match (defaults to id)
     * @return CustomBlacklist
     */

    public static function fetch($value, $name = "id") {
        $results = parent::fetch($value, $name);

        if ($results)
            return new MySqlCustomBypasslist($results, $results['id']);
        else
            return false;
    }

    /* Fetch a list of CustomBlacklist based on the value of a given column.  Returns empty array if no match is found.
     * @param value $value The value to find. (defaults to null, which means return all records in the table)
     * @param string $name The name of the column to match (defaults to null)
     * @return array An array of CustomBlacklist objects
     */

    public static function fetchAll($value = null, $name = null) {
        $resultArr = parent::fetchAll($value, $name);

        $results = [];
        foreach ($resultArr as $id => $group)
            $results[$id] = new MySqlCustomBypasslist($group, $id);

        return $results;
    }
    
    /**
     * Fetch CustomBlacklist objects with user_name instead of id_user
     * @return array an array of CustomBlacklist objects
     */
    public static function fetchWithUsernames() {
        $db = static::connection();
        
        $bl_table = static::$tables['custombypasslist']->name;
        $link_table = static::$tables['user']->name;

        $query = "
            SELECT `$bl_table`.id,`$bl_table`.url,`$bl_table`.description,`$link_table`.user_name as id_user
            FROM `$link_table`, `$bl_table`
            WHERE `$link_table`.id = `$bl_table`.id_user";

        $stmt = $db->prepare($query);

        $stmt->execute();

        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $results[$id] = new MySqlCustomBypasslist($row, $row['id']);
        }
        return $results;
    }

}
