<?php
session_start();
require_once('AbstractSettings.php');
class CreateCustomSettingsTable extends AbstractSettings
{
    private $_dCustomLat;
    private $_dCustomLong;
    private $_sDate;
    private $_sTime;
    private $_sCustomCreation;
    private $_iTimeZone;
    private $_sTableName;

    public function __construct()
    {
        parent::__construct();

        if (!empty($_POST['latitude']) && !empty($_POST['longitude']) && !empty($_POST['timezone'])
            && !empty($_POST['user_date']) && !empty($_POST['user_time'])
        ) {
            $aErrors = array(
                'errorLat' => 'no_errors',
                'errorLong' => 'no_errors',
                'errorDate' => 'no_errors',
                'errorTime' => 'no_errors',
                'errorEmpty' => 'no_errors',
                'errorFatal' => 'no_errors'
            );
            $sRegexTime = '/^([01]?[0-9]|2[0-4]):[0-5][0-9]:[0-5][0-9]/';

            //preg_match('#^[01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $time);
            if (preg_match($sRegexTime, $_POST['user_time']) === 1) {
                $this->_sTime = $_POST['user_time']; //further escaping here
            } else {
                $aErrors['errorTime'] = 'Time format not respected.';
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

            $this->_sCustomCreation = $this->_sDate . ' ' . $this->_sTime;

            $this->_run($this->_sDate);

            $aPlaceName = $this->_getPlaceName($this->_dCustomLat, $this->_dCustomLong);

            if (($aErrors['errorLat'] == 'no_errors') && ($aErrors['errorLat'] == 'no_errors') &&
                ($aErrors['errorDate'] == 'no_errors') && ($aErrors['errorTime'] == 'no_errors') &&
                ($aPlaceName->status == 'ZERO_RESULTS')) {
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
                $_SESSION['customSettings']['datetime'] = $this->_sCustomCreation;
                $_SESSION['customSettings']['timezone'] = $sTimeZone;

                $_SESSION['tempVisibleObjectsTable'] = $this->_sTableName;

                $aSettings = array(
                    'errorFlag' => 'no_errors',
                    'location' => $sLocation,
                    'datetime' => $this->_sCustomCreation,
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
                'errorTime' => 'no_errors',
                'errorFatal' => 'no_errors',
                'errorEmpty' => 'At least one field was empty. Please note that all fields are mandatory.'
            );
            echo json_encode($aErrors);
        }
    }

    private function _run($sDate)
    {
        $iLat = round($this->_dCustomLat);
        $iLong = round($this->_dCustomLong);
        $this->_sTableName = "temp__custom_visibleobjectsfor_" . date('Ymd', strtotime($sDate)) . $iLat . $iLong;


        $check = $this->_checkIfTableAlreadyExists($this->_sTableName);

        if (!empty($check)) {
            //echo "Table <strong> $this->_sTableName </strong> already exists.<br/>";
        } else {
            $this->_createTable($this->_sTableName);
            $this->_populateTable($this->_sTableName, $this->_dCustomLat, $this->_dCustomLong, $this->_sCustomCreation);
        }

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