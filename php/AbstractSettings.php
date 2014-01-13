<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
require_once('DbPdoBase.php');
class AbstractSettings extends DbPdoBase
{
    private $_objId;
    private $_objAltitude;
    private $_objAzimuth;

    public function __construct()
    {
        parent::__construct();
    }

    protected function _populateTable($sTableName, $dLat, $dLong, $sCreation)
    {

        //TODO
        //speed improvement:
        //for the second step, add the final name of the table as argument of the
        //_populateTable. Ex: _populateTable(..., visibleObjectsName = null)
        // if (visibleObjectsName !== null){
        //      $tableToJoinTo = $visibleObjectsName;
        // }
        // next, add a join to the select below, in order to exclude objects already present in
        //visibleObjectsName table.

        $sSql = "
        SELECT
          `id`,
          `ra_float`,
          `dec_float`
        FROM `{$this->_sDbName}`.`object_tmp`
        ";

        foreach ($this->_dbHandle->query($sSql) as $row) {
            $iRa = $row['ra_float'];
            $iDec = $row['dec_float'];
            $aObjectInfo = $this->_getObjectHorizontalCoordinates($iRa, $iDec, $dLat, $dLong, $sCreation);

            $this->_objId = $row['id'];
            $this->_objAzimuth = $aObjectInfo['azimuth'];
            $this->_objAltitude = $aObjectInfo['altitude'];

            //if objects altitude is smaller than 5, don't save it
            //for the second pass,  objects can rise(if enough time has passed), altitude increases and are saved.
            if ($this->_objAltitude > 5) {
                $sSql = "
                INSERT IGNORE INTO `{$this->_sDbName}`.`{$sTableName}`(
                `object_id`,
                `lat`,
                `long`,
                `altitude`,
                `azimuth`,
                `creation`
                )
                VALUES(
                '{$this->_objId}',
                '{$dLat}',
                '{$dLong}',
                '{$this->_objAltitude}',
                '{$this->_objAzimuth}',
                '{$sCreation}'
                )
                ";
                $iResult = $this->_dbHandle->exec($sSql);
            }
        }
    }

    protected function _createTable($sTableName)
    {
        $sSql = "
            CREATE TABLE IF NOT EXISTS `{$this->_sDbName}`.`{$sTableName}`(
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `object_id` int(10) unsigned NOT NULL DEFAULT 0,
            `lat` varchar(255) NOT NULL DEFAULT '',
            `long` varchar(255) NOT NULL DEFAULT '',
            `altitude` varchar(255) NOT NULL DEFAULT '',
            `azimuth` varchar(255) NOT NULL DEFAULT '',
            `creation` TIMESTAMP DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (`id`), UNIQUE (`object_id`)) ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8
        ";

        $iResult = $this->_dbHandle->exec($sSql);
    }

    protected function _checkIfTableAlreadyExists($sTableName)
    {
        $sSql = " SHOW TABLES FROM {$this->_sDbName} LIKE '$sTableName' ";
        $stmt = $this->_dbHandle->prepare($sSql);
        $stmt->execute();
        $res = array();
        //echo '<pre>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $row;
        }
        return $res;
    }

    protected function _getObjectHorizontalCoordinates($RA, $Dec, $Lat, $Long, $Date)
    {

        //calculate day Offset (x number of days from J2000.0):
        $iDate = strtotime($Date);
        $iJ2000Date = mktime(12, 0, 0, 1, 1, 2000);
        $dayOffset = $iDate - $iJ2000Date;
        $dayOffset = (double)$dayOffset / (60 * 60 * 24);
        //var_dump(date('Y-m-d h:i:s',$iJ2000Date));
        //var_dump($dayOffset);


        //calculate LST (Local Sideral Time):
        $LST = (double)(100.46 + (0.985647 * $dayOffset) + $Long + (15 * (date("H", $iDate) + (date("i", $iDate) / 60))));
        //Add or subtract multiples of 360 to bring LST in range 0 to 360 degrees:
        if ($LST < 0) {
            $LST = $LST + 360;
        } elseif ($LST > 360) {
            $LST = $LST - 360;
        }
        //var_dump($LST);


        // Calculate HA (Hour Angle)
        $HA = ($LST - $RA + 360) % 360;
        //var_dump($HA);


        // HA, DEC, Lat to Alt, AZ
        $x = cos($HA * (pi() / 180)) * cos($Dec * (pi() / 180));
        $y = sin($HA * (pi() / 180)) * cos($Dec * (pi() / 180));
        $z = sin($Dec * (pi() / 180));

        $xhor = $x * cos((90 - $Lat) * (pi() / 180)) - $z * sin((90 - $Lat) * (pi() / 180));
        $yhor = $y;
        $zhor = $x * sin((90 - $Lat) * (pi() / 180)) + $z * cos((90 - $Lat) * (pi() / 180));

        $az = atan2($yhor, $xhor) * (180 / pi()) + 180;
        $alt = asin($zhor) * (180 / pi());

        //var_dump($az);
        //var_dump($alt);


        $aObjectName['azimuth'] = $az;
        $aObjectName['altitude'] = $alt;
        return $aObjectName;
    }

    protected function _createTmpObjectTable(){
        $sTableName = "object_tmp";
        $query = "
        CREATE TABLE IF NOT EXISTS `{$this->_sDbName}`.`{$sTableName}`(
         `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
         `name` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `other_name` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `type` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `constellation` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `ra` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `dec` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `ra_float` varchar(30) CHARACTER SET latin1 NOT NULL,
         `dec_float` varchar(30) CHARACTER SET latin1 NOT NULL,
         `mag` decimal(3,1) NOT NULL DEFAULT '0.0',
         `subr` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `u2k` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `ti` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `size_max` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `size_min` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `pa` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `class` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `nsts` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `brstr` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `bchm` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `ngc_description` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
         `notes` text CHARACTER SET latin1 NOT NULL,
         PRIMARY KEY (`id`)
        ) ENGINE=MyIsam DEFAULT CHARSET=utf8
        ";
        $this->_dbHandle->exec($query);
    }
}