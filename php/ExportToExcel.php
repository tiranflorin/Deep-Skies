<?php
session_start();
require_once('DbPdoBase.php');
require_once('lib/ExportBase.php');
class ExportToExcel extends DbPdoBase{
	private $_tempTable;
	private $_sourceTable;

	public function __construct(){
        parent::__construct();

		if (isset($_SESSION['tempVisibleObjectsTable'])) {
            $this->_tempTable = $_SESSION['tempVisibleObjectsTable'];
        } else {
            $sDate = date('Ymd');
            $this->_tempTable = "temp__default_visibleobjectsfor_{$sDate}4724";
        }
        $this->_sourceTable = "object";

        $this->_exportToExcel();
		//print_r($_SESSION['object_id']);
	}

	private function _exportToExcel(){
		$sWhereCondition = " AND altaz_coord.object_id IN('";
        $sWhereCondition .= implode(',',$_SESSION['object_id']);
        $sWhereCondition  = str_replace(',', "','", $sWhereCondition);
        $sWhereCondition .= "') ";
		//echo $sWhereCondition;

		$sSql = "
		SELECT
            altaz_coord.object_id as `Object_id`,    
            source.name as `Name1`,
            source.other_name as `Name2`,
            source.type as `ObjType`,
            source.constellation as `Constellation`,
            source.mag as `ObjMagnitude`,
            source.size_min as `ObjMinSize`,
            source.size_max as `ObjMaxSize`, 
            source.ngc_description as `Ngc_desc`,
            source.notes as `Other_notes`,
            altaz_coord.altitude as `obj_altitude`,
            altaz_coord.azimuth as `obj_azimuth`
        FROM `{$this->_sDbName}`.`{$this->_sourceTable}`  as source
        LEFT JOIN `{$this->_sDbName}`.`{$this->_tempTable}` as altaz_coord
            ON altaz_coord.object_id = source.id
        WHERE 1
            AND `altitude` > 10
            {$sWhereCondition}
        ORDER BY 
            `ObjMagnitude`
        LIMIT 20";

        /*
        echo "<pre>";
        echo $sSql;
        die();
        */

        $stmt = $this->_dbHandle->prepare($sSql);
        $stmt->execute();
        $res = array();
        //echo '<pre>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $res[]=$row;
        }
        /*
        echo "<pre>";
        var_dump($res);
        die();
        */
        exportToXls($res);
	}
}

$oObj = new ExportToExcel();