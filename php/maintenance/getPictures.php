<?php
	ini_set('max_execution_time', 86400);

	$imgHeight = 25;
	$imgWidth  = 25;
	$sUrlImage = '';

	for($i=7001;$i<=7840;$i++)
	{
		$iNgcNumber = $i;

		$sUrlGetObjectInfo = "http://stdatu.stsci.edu/cgi-bin/dss_form?target=ngc+{$iNgcNumber}&resolver=SIMBAD";


	 	$ch = curl_init(); 
	    curl_setopt($ch, CURLOPT_URL, $sUrlGetObjectInfo); 
	    //return the transfer as a string: 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	    $output = curl_exec($ch);
	    $o2 = explode("<input name=r", $output);
	    $o3 = explode("<select name=e>",$o2[1]);
	    $rest = substr($o3[0], 8);
	    $rightA = substr($rest,0,11);
	    $declination = substr($rest,-14,11);
	    
	    $sUrlImage = "http://stdatu.stsci.edu/cgi-bin/dss_search?v=poss2ukstu_red&r={$rightA}&d={$declination}&e=J2000&h={$imgHeight}&w={$imgWidth}&f=gif&c=none&fov=NONE&v3=";
	   	$sUrlImage = str_replace(" ", "+", $sUrlImage);
	   	
		curl_close($ch);

	    $pictureName = "NGC_$iNgcNumber";
	    $picturePath = "ngcPictures/$pictureName.gif";
	    $ch = curl_init($sUrlImage);
		$fp = fopen($picturePath, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}	
?>