<?php

namespace UserFrosting;

class MySqlWorkingHours extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('workinghours');
        parent::__construct($properties, $id);
    }
    

}

?>
