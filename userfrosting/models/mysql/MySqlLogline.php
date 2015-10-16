<?php

namespace UserFrosting;

class MySqlLogline extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('logline');
        parent::__construct($properties, $id);
    }
    

}

?>
