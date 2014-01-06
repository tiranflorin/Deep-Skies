<?php
//replace values with real ones
//rename to DbPdo.php
$dbh = new PDO('mysql:host=host;dbname=dbname', 'username', 'password', array(
    PDO::ATTR_PERSISTENT => true
));
