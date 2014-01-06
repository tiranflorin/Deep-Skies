<?php
date_default_timezone_set('UTC');

$RA = 250.425; // 16 h 41.7 m * 15
$Dec = 36.46667; // 35 ° 30 m
//$Lat = 52.5; // normal decimal latitude
//$Long = -1.9166667; // normal decimal latitude

//cluj coordinates:
$Lat = 46 + (46/60) + (48/3600);
$Long = 23 + (35/60) + (24/3600);

function getObjectHorizontalCoordinates($RA, $Dec,$Lat, $Long, $Date){

//calculate day Offset (x number of days from J2000.0):
$iDate = strtotime($Date);
$iJ2000Date = mktime(12, 0, 0, 1, 1, 2000);
$dayOffset = $iDate - $iJ2000Date;
$dayOffset = (double)$dayOffset/(60*60*24);
//var_dump(date('Y-m-d h:i:s',$iJ2000Date));
//var_dump($dayOffset);


//calculate LST (Local Sideral Time):
$LST = (double)(100.46 + (0.985647 * $dayOffset) + $Long + (15 * (date("H",$iDate)+ (date("i",$iDate)/ 60))));
//Add or subtract multiples of 360 to bring LST in range 0 to 360 degrees:
if($LST < 0){
$LST = $LST + 360;
}
elseif ($LST > 360) {
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


//getObjectHorizontalCoordinates($RA, $Dec, $Lat, $Long, '2013-08-29 23:10:00');
