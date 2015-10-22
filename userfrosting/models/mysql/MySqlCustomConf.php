<?php

namespace UserFrosting;

class MySqlCustomConf extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('custom_conf');
        parent::__construct($properties, $id);
    }
    

}

?>
