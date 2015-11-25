<?php

namespace UserFrosting;

/* This class is responsible for retrieving Salle object(s) from the database, checking for existence, etc. */

class MySqlLoglineLoader extends MySqlObjectLoader {

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
            return new MySqlLogline($results, $results['id']);
        else
            return false;
    }

    /* Fetch a list of salle based on the value of a given column.  Returns empty array if no match is found.
     * @param value $value The value to find. (defaults to null, which means return all records in the table)
     * @param string $name The name of the column to match (defaults to null)
     * @return array An array of Salle objects
     */

    public static function fetchAll($value = null, $name = null) {
        $resultArr = parent::fetchAll($value, $name);

        $results = [];
        foreach ($resultArr as $id => $group)
            $results[$id] = new MySqlLogline($group, $id);

        return $results;
    }

    public static function hits4day($day, $network = "", $mask = "") {
        $db = static::connection();

        $query = "SELECT COUNT(*)"
                . "FROM uf_logline "
                . "WHERE DATE(datelog) = ? ";
        $args = array($day);
        if ($network != "") {
            $query.="AND (INET_ATON(host_ip) & INET_ATON(?)) = INET_ATON(?)";
            $args = array($day, $mask, $network);
        }
        $stmt = $db->prepare($query);
        $stmt->execute($args);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result["COUNT(*)"];
    }

    public static function blocks4day($day, $salle = "") {
        $db = static::connection();

        $query = "SELECT COUNT(*)"
                . "FROM uf_blockedsitelog "
                . "WHERE DATE(datelog) = ? ";

        $args = array($day);
        if ($salle != "") {
            $query.="AND id_salle = ?";
            $args = array($day, $salle);
        }
        $stmt = $db->prepare($query);
        $stmt->execute($args);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result["COUNT(*)"];
    }

    public static function top10hits($daystart, $dayend, $network = "", $mask = "") {
        $db = static::connection();

        $query = "SELECT COUNT(*) as nbhits,substring_index(substring_index(substring_index(url,'http://',-1),'/',1),':',1) as url " .
                "from uf_logline " .
                "where DATE(datelog) between ? and ? " .
                "group by substring_index(substring_index(substring_index(url,'http://',-1),'/',1),':',1) " .
                "order by count(*) desc " .
                "limit 10";

        $args = array($daystart, $dayend);
        if ($network != "") {
            $query.="AND (INET_ATON(host_ip) & INET_ATON(?)) = INET_ATON(?)";
            $args = array($daystart, $dayend, $network, $mask);
        }
        $stmt = $db->prepare($query);
        $stmt->execute($args);
        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;
    }
    public static function top10blocks($daystart, $dayend, $salle = "") {
        $db = static::connection();

        $query = "SELECT COUNT(*) as nbblocks,substring_index(substring_index(substring_index(url,'http://',-1),'/',1),':',1) as url " .
                "from uf_blockedsitelog " .
                "where DATE(datelog) between ? and ? " .
                "group by substring_index(substring_index(substring_index(url,'http://',-1),'/',1),':',1) " .
                "order by count(*) desc " .
                "limit 10";

        $args = array($daystart, $dayend);
        if ($salle != "") {
            $query.="AND id_salle = ?";
            $args = array($daystart, $dayend, $salle);
        }
        $stmt = $db->prepare($query);
        $stmt->execute($args);
        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;
    }
    
    public static function blocksPerCategory($daystart, $dayend, $salle = "") {
        $db = static::connection();

        $query = "SELECT COUNT(*) as nbblocks,category " .
                "from uf_blockedsitelog " .
                "where DATE(datelog) between ? and ? " .
                "group by category ";

        $args = array($daystart, $dayend);
        if ($salle != "") {
            $query.="AND id_salle = ?";
            $args = array($daystart, $dayend, $salle);
        }
        $stmt = $db->prepare($query);
        $stmt->execute($args);
        $results = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;
    }

}
