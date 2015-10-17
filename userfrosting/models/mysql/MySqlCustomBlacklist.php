<?php

namespace UserFrosting;

class MySqlCustomBlacklist extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('customblacklist');
        parent::__construct($properties, $id);
    }
    

    

}

?>
