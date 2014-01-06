<?php
//replace values with real ones
//rename to DbPdoBase.php and modify the class name(class DbPdoBase)
class DbPdoBaseDummy {
    protected $_dbHandle;
    protected $_sDbName;

    public function __construct(){
        $this->_dbHandle = new PDO('mysql:host=host;dbname=dbname', 'username', 'password', array(
            PDO::ATTR_PERSISTENT => true
        ));
        $this->_sDbName = 'dbname';
    }
} 