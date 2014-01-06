<?php
require_once('AbstractSettings.php');
class CreateDefaultSettingsTable extends AbstractSettings
{

    private $_dDefaultLat;
    private $_dDefaultLong;
    private $_sDefaultCreation;

    public function __construct()
    {
        parent::__construct();

        $this->_dDefaultLat = 46 + (46 / 60) + (48 / 3600); //Cluj lat
        $this->_dDefaultLong = 23 + (35 / 60) + (24 / 3600); //Cluj long

        $iStartDate = strtotime(date('Ymd'));
        $iEndDate = strtotime(date('Ymd', strtotime("+5 days")));
        for ($i = $iStartDate; $i <= $iEndDate; $i = $i + 86400) {
            //echo date('Y-m-d', $i);
            //echo "<br>";
            $sCurrentDate = date('Ymd', $i);
            $this->_run($sCurrentDate);
        }
    }

    private function _run($sDate)
    {
        $iLat = round($this->_dDefaultLat);
        $iLong = round($this->_dDefaultLong);
        $sTableName = "temp__default_visibleobjectsfor_" . $sDate . $iLat . $iLong;
        $this->_sDefaultCreation = date('Y-m-d', strtotime($sDate)) . ' 23:10:00';

        /*
        echo $sTableName;
        echo $this->_sDefaultCreation;
        die();
        */

        $check = $this->_checkIfTableAlreadyExists($sTableName);

        if(!empty($check)){
            echo "Table <strong> $sTableName </strong> already exists.<br/>";
        }
        else{
            $this->_createTable($sTableName);
            $this->_populateTable($sTableName, $this->_dDefaultLat, $this->_dDefaultLong, $this->_sDefaultCreation);
        }
    }

}

$oObj = new CreateDefaultSettingsTable();