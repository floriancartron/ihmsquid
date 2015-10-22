<?php

namespace UserFrosting;

class MySqlCustomConfItem extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('custom_conf_items');
        parent::__construct($properties, $id);
    }
    

}

?>
