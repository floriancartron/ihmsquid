<?php

namespace UserFrosting;

class MySqlBlacklistCategories extends MySqlDatabaseObject {

    public function __construct($properties, $id = null) {
        $this->_table = static::getTable('blacklist_categories');
        parent::__construct($properties, $id);
    }
    

}

?>
