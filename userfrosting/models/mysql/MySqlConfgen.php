<?php

namespace UserFrosting;

class MySqlConfgen extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('confgen');
        parent::__construct($properties, $id);
    }
    

}

?>
