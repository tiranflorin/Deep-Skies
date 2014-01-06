<?php
require_once('DbPdoBase.php');
require_once('PaginationResults.php');
session_start();
class RetrieveObjectsBase extends DbPdoBase
{
    private $_tempTable;
    private $_sourceTable;
    private $_imagePaths;
    private $_iResultsPerPage;

    public function __construct()
    {
        parent::__construct();
        if (isset($_SESSION['tempVisibleObjectsTable'])) {
            $this->_tempTable = $_SESSION['tempVisibleObjectsTable'];
        } else {
            $sDate = date('Ymd');
            $this->_tempTable = "temp__default_visibleobjectsfor_{$sDate}4724";
        }
        $this->_sourceTable = "object";
        $this->_imagePaths = "image_paths";

        $this->_iResultsPerPage = 15; //initial value
    }

    public function _buildMarkup($Name1, $Name2, $Constellation, $ObjType, $ObjMagnitude, $ObjMinSize, $ObjMaxSize, $obj_altitude, $obj_azimuth, $Ngc_desc, $Other_notes, $imgThumb, $imgLarge)
    {
        if ($imgThumb == 'default_thumbnail') {
            $usedImgThumb = 'ngcPics/thumbnails_100x100/default_thumb.jpg';
            $usedImgLarge = 'ngcPics/large_800x800/default_large.jpg';
        } else {
            $usedImgThumb = "ngcPics/thumbnails_100x100/$imgThumb";
            $usedImgLarge = "ngcPics/large_800x800/$imgLarge";
        }
        $obj_altitude = round($obj_altitude, 2);
        $obj_azimuth = round($obj_azimuth, 2);
        $sMarkup = "<div class='media col-lg-12'>
              <a href='" . $usedImgLarge . "' class='objThumb pull-left'  title='" . $Name1 . " large'>
                <img src='" . $usedImgThumb . "' alt='" . $Name1 . " thumbnail'>
              </a>
              <div class='media-body'>
                <h4 class='media-heading' style='margin-bottom:10px;'> " . $Name1 . " <small>(" . $Name2 . ")</small></h4>
                  <div class='media-list'>
                    <ul class='list-inline'>
                      <li> <span class='objAttr'>Const</span>: <span class='objVal'> " . $Constellation . " </span></li>
                      <li> <span class='objAttr'>Type</span>: <span class='objVal'> " . $ObjType . "</span> </li>
                      <li> <span class='objAttr'>Mag</span>: <span class='objVal'> " . $ObjMagnitude . "</span> </li>
                      <li> <span class='objAttr'>Min size</span>: <span class='objVal'> " . $ObjMinSize . " </span></li>
                      <li> <span class='objAttr'>Max size</span>: <span class='objVal'> " . $ObjMaxSize . " </span></li>
                      <li> <span class='objAttr'>Altitude</span>: <span class='objVal'> " . $obj_altitude . "&deg;</span> </li>
                      <li> <span class='objAttr'>Azimuth</span>: <span class='objVal'> " . $obj_azimuth . "&deg;</span> </li>
                    </ul>
                  </div>
                  <div class='media-list'>
                    <ul class='list-inline'>

                      <li> <span class='objAttr'>NGC Description</span>: <span class='objVal'> " . $Ngc_desc . "</span> </li>
                      <li> <span class='objAttr'>Other Notes</span>: <span class='objVal'> " . $Other_notes . "</span> </li>
                    </ul>
                  </div>
                  </div>
            </div>
            ";
        return $sMarkup;
    }

    public function _saveDisplayedObjectIdOnSession($iId)
    {
        $_SESSION['object_id'][] = $iId;
        //enforce unique object ids:
        $_SESSION['object_id'] = array_unique($_SESSION['object_id']);
    }

    public function _getVisibleObjectsBase($sWhereCondition1,$sWhereCondition2,$sWhereCondition3, $iPageId, $iPageNumber, $bPage)
    {

        $iPageLimit = $iPageId * $this->_iResultsPerPage;

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
            altaz_coord.azimuth as `obj_azimuth`,
            IFNULL(img.thumb, 'default_thumbnail') as `thumb`,
            IFNULL(img.full_size, 'default_full_size') as `full_size`
        FROM `{$this->_sDbName}`.`{$this->_sourceTable}`  as source
        LEFT JOIN `{$this->_sDbName}`.`{$this->_tempTable}` as altaz_coord
            ON altaz_coord.object_id = source.id
        LEFT JOIN `{$this->_sDbName}`.`{$this->_imagePaths}` as img
            ON img.object_id = source.id
        WHERE 1
            AND `altitude` > 10
            {$sWhereCondition3}
            {$sWhereCondition1}
            {$sWhereCondition2}
        ORDER BY
            `ObjMagnitude`
        LIMIT {$iPageLimit},{$this->_iResultsPerPage}
        ";

        /*
        echo "<pre>";
        echo $sSql;
        die;
        */

        $stmt = $this->_dbHandle->prepare($sSql);
        $stmt->execute();
        $res = array();


        $sSqlCountResults = "
        SELECT
            *
        FROM `{$this->_sDbName}`.`{$this->_sourceTable}`  as source
        LEFT JOIN `{$this->_sDbName}`.`{$this->_tempTable}` as altaz_coord
            ON altaz_coord.object_id = source.id
        LEFT JOIN `{$this->_sDbName}`.`{$this->_imagePaths}` as img
            ON img.object_id = source.id
        WHERE 1
            AND `altitude` > 10
            {$sWhereCondition3}
            {$sWhereCondition1}
            {$sWhereCondition2}
        ORDER BY
            NULL
        ";

        $stmt2 = $this->_dbHandle->prepare($sSqlCountResults);
        $stmt2->execute();
        $iResultsCount = $stmt2->rowCount();

        /*
        echo '<pre>';
        echo $iResultsCount;
        die;
        */

        if ($iResultsCount > 0) {
            $paginationCount = PaginationResults::_getPagination($iResultsCount, $this->_iResultsPerPage);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $res[] = $row;
            }
            //print_r($res);
            //echo json_encode($res);
            $data = "";
            $iCounter = count($res);
            $this->_destroyObjectIdsFromSession();
            for ($i = 0; $i < $iCounter; $i++) {
                $this->_saveDisplayedObjectIdOnSession($res[$i]['Object_id']);
                $data .= $this->_buildMarkup($res[$i]['Name1'], $res[$i]['Name2'], $res[$i]['Constellation'], $res[$i]['ObjType'],
                    $res[$i]['ObjMagnitude'], $res[$i]['ObjMinSize'], $res[$i]['ObjMaxSize'], $res[$i]['obj_altitude'],
                    $res[$i]['obj_azimuth'], $res[$i]['Ngc_desc'], $res[$i]['Other_notes'], $res[$i]['thumb'], $res[$i]['full_size']);
                if ($i % 3 == 2) {
                    $data .= "<div style='clear:both'></div>";
                }
            }
            //echo "<pre>";
            //print_r($_SESSION['object_id']);

            echo '<div id="resultsDetails">';
            echo "<p>Objects found: $iResultsCount ($paginationCount pages).</p>";
            echo "<p>Results displayed per page: $this->_iResultsPerPage.</p>";
            echo '</div>';

            $oObj1 = new PaginationResults($iPageNumber, $iPageId, $paginationCount, $bPage);
            echo "<div style='clear:both'></div>";
            echo $data;
            $oObj2 = new PaginationResults($iPageNumber, $iPageId, $paginationCount, $bPage);
        } else {
            echo '<div id="resultsDetails">';
            echo "<p>No results were found for current filters.</p>";
            echo "<p>Please recheck <em>selected</em> <strong>magnitude</strong>, <strong>object type</strong> or <strong>constellation</strong>. Thank you!</p>";
            echo '</div>';
        }
    }

    public function _destroyObjectIdsFromSession()
    {
        $_SESSION['object_id'] = array();
    }
}