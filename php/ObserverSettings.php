<?php

class ObserverSettings{
	public function __construct(){
		if(!empty($_POST)){
			$this->_setDefaultObserverSettings();
		}
		else{
			$this->_setSpecificObserverSettings();
		}

	}

	private function _setDefaultObserverSettings(){
		$aSettings = array();
		return $aSettings;
	}

	private function _setSpecificObserverSettings(){
		$Lat = 46 + (46/60) + (48/3600);
		$Long = 23 + (35/60) + (24/3600);
		$results = $this->_getPlaceName($Lat,$Long);
		echo $results->results[0]->formatted_address;
		//return $aSettings;
	}	

	private function _getPlaceName($latitude, $longitude){

		
	   //This below statement is used to send the data to google maps api and get the place name in different formats. we need to convert it as required. 
	   $geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false');
	   $output= json_decode($geocode);
	   //Here "formatted_address" is used to display the address in a user friendly format.
	   //echo $output->results[0]->formatted_address;
	  	
	   return $output;

	}

}

$oTest = new ObserverSettings();