<?php

namespace UserFrosting;

class MySqlCustomWhitelist extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('customwhitelist');
        parent::__construct($properties, $id);
    }
    

    

}

?>
