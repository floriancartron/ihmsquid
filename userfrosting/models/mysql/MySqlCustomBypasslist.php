<?php

namespace UserFrosting;

class MySqlCustomBypasslist extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('custombypasslist');
        parent::__construct($properties, $id);
    }
    

    

}

?>
