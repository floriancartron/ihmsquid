<?php

namespace UserFrosting;

class MySqlExtSites extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('ext_sites');
        parent::__construct($properties, $id);
    }
    

    

}

?>
