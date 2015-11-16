<?php

namespace UserFrosting;

/* This class is responsible for retrieving Salle object(s) from the database, checking for existence, etc. */

class MySqlConfgenLoader extends MySqlObjectLoader {

    protected static $_table;       // The table whose rows this class represents. Must be set in the child concrete class.   

    /* Determine if a group exists based on the value of a given column.  Returns true if a match is found, false otherwise.
     * @param value $value The value to find.
     * @param string $name The name of the column to match (defaults to id)
     * @return bool
     */

    public static function exists($value, $name = "id") {
        return parent::fetch($value, $name);
    }

    /* Fetch a single group based on the value of a given column.  For non-unique columns, it will return the first entry found.  Returns false if no match is found.
     * @param value $value The value to find.
     * @param string $name The name of the column to match (defaults to id)
     * @return Salle
     */

    public static function fetch($value, $name = "id") {
        $results = parent::fetch($value, $name);

        if ($results)
            return new MySqlConfgen($results, $results['id']);
        else
            return false;
    }




}
