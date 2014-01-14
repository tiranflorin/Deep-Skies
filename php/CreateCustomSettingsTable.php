<?php
session_start();
require_once('AbstractSettings.php');
class CreateCustomSettingsTable extends AbstractSettings
{
    private $_dCustomLat;
    private $_dCustomLong;
    private $_sDate;
    private $_sTime1;
    private $_sTime2;
    private $_sCustomCreation1;
    private $_sCustomCreation2;
    private $_iTimeZone;
    private $_sTableName;

    public function __construct()
    {
        parent::__construct();

        if (!empty($_POST['latitude']) && !empty($_POST['longitude']) && !empty($_POST['timezone'])
            && !empty($_POST['user_date']) && !empty($_POST['user_time1']) && !empty($_POST['user_time2'])
        ) {
            $aErrors = array(
                'errorLat' => 'no_errors',
                'errorLong' => 'no_errors',
                'errorDate' => 'no_errors',
                'errorTime1' => 'no_errors',
                'errorTime2' => 'no_errors',
                'errorEmpty' => 'no_errors',
                'errorFatal' => 'no_errors'
            );
            $sRegexTime = '/^([01]?[0-9]|2[0-4]):[0-5][0-9]:[0-5][0-9]/';

            //preg_match('#^[01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $time);
            if (preg_match($sRegexTime, $_POST['user_time1']) === 1) {
                $this->_sTime1 = $_POST['user_time1']; //further escaping here
            } else {
                $aErrors['errorTime1'] = 'Start time format not respected.';
            }

            if (preg_match($sRegexTime, $_POST['user_time2']) === 1) {
                $this->_sTime2 = $_POST['user_time2']; //further escaping here
            } else {
                $aErrors['errorTime2'] = 'End time format not respected.';
            }

            if ($this->_isValidDate($_POST['user_date']) === true) {
                $this->_sDate = $_POST['user_date'];
            } else {
                $aErrors['errorDate'] = 'Invalid date. Please recheck!';
            }

            if (is_numeric($_POST['latitude']) && ($_POST['latitude'] >= -90) && ($_POST['latitude'] <= 90)) {
                $this->_dCustomLat = round($_POST['latitude'], 2);
            } else {
                $aErrors['errorLat'] = 'Invalid number for latitude. Please recheck!';
            }

            if (is_numeric($_POST['longitude']) && ($_POST['longitude'] >= -180) && ($_POST['longitude'] <= 180)) {
                $this->_dCustomLong = round($_POST['longitude'], 2);
            } else {
                $aErrors['errorLong'] = 'Invalid number for longitude. Please recheck!';
            }


            $this->_iTimeZone = $_POST['timezone'];

            $this->_sCustomCreation1 = $this->_sDate . ' ' . $this->_sTime1;

            //custom creation can have a different date.
            //the next day for example.
            $timeToday1 = $this->_sDate . ' ' . $this->_sTime1;
            $timeToday2 = $this->_sDate . ' 23:59:59';
            $time3 = $this->_sDate . ' ' . $this->_sTime2;
            $unixTime1 = strtotime($timeToday1);
            $unixTime2 = strtotime($timeToday2);
            $unixTime3 = strtotime($time3);
            if (($unixTime3 < $unixTime1 ) && $unixTime3 < $unixTime2 ) {
                //$tomorrow = date('DAY +1', strtotime($this->_sDate));
                $datetime = new DateTime('tomorrow');
                $tomorrow =  $datetime->format('Y-m-d');
                $this->_sCustomCreation2 = $tomorrow . ' ' . $this->_sTime2;
            } else {
                $this->_sCustomCreation2 = $this->_sDate . ' ' . $this->_sTime2;
            }


            $this->_run($this->_sDate);

            $aPlaceName = $this->_getPlaceName($this->_dCustomLat, $this->_dCustomLong);

            if (($aErrors['errorLat'] == 'no_errors') && ($aErrors['errorLat'] == 'no_errors') &&
                ($aErrors['errorDate'] == 'no_errors') && ($aErrors['errorTime1'] == 'no_errors') &&
                ($aErrors['errorTime2'] == 'no_errors') &&($aPlaceName->status == 'ZERO_RESULTS')) {
                $aErrors['errorFatal'] = 'Unable to find your location and calculate visible objects
                for current settings. Please change/recheck more carefully your coordinates.';
            }

            $bErrorsFound = false;
            foreach ($aErrors as $key => $errorName) {
                if ($errorName != 'no_errors') {
                    $bErrorsFound = true;
                    break;
                }
            }
            if ($bErrorsFound === false) {


                $sLocation = $aPlaceName->results[0]->formatted_address;
                $sLocation .= " ($this->_dCustomLat N, $this->_dCustomLong E)";


                if ($this->_iTimeZone < 0) {
                    $sTimeZone = "GMT -$this->_iTimeZone:00";
                } else {
                    $sTimeZone = "GMT +$this->_iTimeZone:00";
                }

                $_SESSION['boolCustomSettings'] = true;

                $_SESSION['customSettings']['location'] = $sLocation;
                $_SESSION['customSettings']['datetime1'] = $this->_sCustomCreation1;
                $_SESSION['customSettings']['datetime2'] = $this->_sCustomCreation2;
                $_SESSION['customSettings']['timezone'] = $sTimeZone;

                $_SESSION['tempVisibleObjectsTable'] = $this->_sTableName;

                $aSettings = array(
                    'errorFlag' => 'no_errors',
                    'location' => $sLocation,
                    'datetime1' => $this->_sCustomCreation1,
                    'datetime2' => $this->_sCustomCreation2,
                    'timezone' => $sTimeZone
                );
                echo json_encode($aSettings);
            } else {
                $aErrors['errorFlag'] = 'errors_found';
                echo json_encode($aErrors);
            }
        } else {
            $aErrors = array(
                'errorFlag' => 'errors_found',
                'errorLat' => 'no_errors',
                'errorLong' => 'no_errors',
                'errorDate' => 'no_errors',
                'errorTime1' => 'no_errors',
                'errorTime2' => 'no_errors',
                'errorFatal' => 'no_errors',
                'errorEmpty' => 'At least one field was empty. Please note that all fields are mandatory.'
            );
            echo json_encode($aErrors);
        }
    }

    private function _run($sDate)
    {
        //save computing time: limit the nb of objects for which we'll calculate alt-az coordinates:
        $sTableName = "object_tmp";

        //create object_tmp
        $this->_createTmpObjectTable();

        //eliminate circumpolar south objects:
        $desiredDeclination = - $this->_dCustomLat;
        $insert = "
        INSERT INTO `{$this->_sDbName}`.`{$sTableName}`
        SELECT
          *
        FROM `{$this->_sDbName}`.`object`
        WHERE dec_float > {$desiredDeclination}
        ";

        $this->_dbHandle->exec($insert);



        $iLat = round($this->_dCustomLat);
        $iLong = round($this->_dCustomLong);
        $this->_sTableName = "temp__custom_visibleobjectsfor_" . date('Ymd', strtotime($sDate)) . $iLat . $iLong;

        //create visible objects table:
        $this->_createTable($this->_sTableName);

        //calculate objects visible at interval 1:
        $this->_populateTable($this->_sTableName, $this->_dCustomLat, $this->_dCustomLong, $this->_sCustomCreation1);

        //calculate objects visible at interval 2:
        $this->_populateTable($this->_sTableName, $this->_dCustomLat, $this->_dCustomLong, $this->_sCustomCreation2);

        //drop object_tmp table if it is no longer needed:
        $this->_dbHandle->exec("DROP TABLE IF EXISTS `{$this->_sDbName}`.`{$sTableName}`");

    }

    private function _getPlaceName($latitude, $longitude)
    {

        //This below statement is used to send the data to google maps api and get the place name in different formats. we need to convert it as required.
        $geocode = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $latitude . ',' . $longitude . '&sensor=false');
        $output = json_decode($geocode);
        //Here "formatted_address" is used to display the address in a user friendly format.
        //echo $output->results[0]->formatted_address;

        return $output;

    }

    private function _isValidDate($date)
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
    }

}

$oObj = new CreateCustomSettingsTable();