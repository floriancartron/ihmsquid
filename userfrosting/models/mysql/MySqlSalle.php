<?php

namespace UserFrosting;

class MySqlSalle extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('salle');
        parent::__construct($properties, $id);
    }
    

}

?>
