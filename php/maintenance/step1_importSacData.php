<?php
	//include fisierele necesare:
	require_once('../DbPdo.php');
	$handle = @fopen("files/sac81.txt", "r");
	if ($handle) {
	    while (($buffer = fgets($handle)) !== false) {
	        //echo $buffer;
	        $pieces = explode("|", $buffer);
	        $var1 = trim($pieces[1],'"');
	        $var2 = trim($pieces[2],'"');
	        $var3 = trim($pieces[3],'"');
	        $var4 = trim($pieces[4],'"');
	        $var5 = trim($pieces[5],'"');
	        $var6 = trim($pieces[6],'"');
	        $var7 = trim($pieces[7],'"');
	        $var8 = trim($pieces[8],'"');
	        $var9 = trim($pieces[9],'"');
	        $var10 = trim($pieces[10],'"');
	        $var11 = trim($pieces[11],'"');
	        $var12 = trim($pieces[12],'"');
	        $var13 = trim($pieces[13],'"');
	        $var14 = trim($pieces[14],'"');
	        $var15 = trim($pieces[15],'"');
	        $var16 = trim($pieces[16],'"');
	        $var17 = trim($pieces[17],'"');
	        $var18 = trim($pieces[18],'"');
	        $var19 = trim($pieces[19],'"');

	        $var1 = str_replace(' ', '', $var1);
	        $var2 = str_replace(' ', '', $var2);
	        $var3 = str_replace(' ', '', $var3);
	        $var4 = str_replace(' ', '', $var4);
	        $var5 = str_replace(' ', '', $var5);
	        $var6 = str_replace(' ', '', $var6);
	        $var7 = str_replace(' ', '', $var7);
	        $var8 = str_replace(' ', '', $var8);
	        $var9 = str_replace(' ', '', $var9);
	        $var10 = str_replace(' ', '', $var10);
	        $var11 = str_replace(' ', '', $var11);
	        $var12 = str_replace(' ', '', $var12);
	        $var13 = str_replace(' ', '', $var13);
	        $var14 = str_replace(' ', '', $var14);
	        $var15 = str_replace(' ', '', $var15);
	        $var16 = str_replace(' ', '', $var16);
	        $var17 = str_replace(' ', '', $var17);
	        $var18 = str_replace(' ', '', $var18);
	        $var19 = str_replace(' ', '', $var19);

            //echo $pieces[0];

	        $var1 = mysql_real_escape_string($var1);
	        $var2 = mysql_real_escape_string($var2);
	        $var3 = mysql_real_escape_string($var3);
	        $var4 = mysql_real_escape_string($var4);
	        $var5 = mysql_real_escape_string($var5);
	        $var6 = mysql_real_escape_string($var6);
	        $var7 = mysql_real_escape_string($var7);
	        $var8 = mysql_real_escape_string($var8);
	        $var9 = mysql_real_escape_string($var9);
	        $var10 = mysql_real_escape_string($var10);
	        $var11 = mysql_real_escape_string($var11);
	        $var12 = mysql_real_escape_string($var12);
	        $var13 = mysql_real_escape_string($var13);
	        $var14 = mysql_real_escape_string($var14);
	        $var15 = mysql_real_escape_string($var15);
	        $var16 = mysql_real_escape_string($var16);
	        $var17 = mysql_real_escape_string($var17);
	        $var18 = mysql_real_escape_string($var18);
	        $var19 = mysql_real_escape_string($var19);

	        	
	        $sSql =  "
	        	INSERT INTO `object`(
	        		`name`, `other_name`, `type`, `constellation`, `ra`, `dec`, `mag`, `subr`, `u2k`, `ti`, `size_max`, `size_min`, `pa`, `class`, `nsts`, `brstr`, `bchm`, `ngc_description`, `notes`
	        	)
	        	VALUES(
	        		'$var1',
	        		'$var2',
	        		'$var3',	
	        		'$var4',
	        		'$var5',
	        		'$var6',
	        		'$var7',
	        		'$var8',
	        		'$var9',
	        		'$var10',
	        		'$var11',
	        		'$var12',
	        		'$var13',
	        		'$var14',
	        		'$var15',
	        		'$var16',
	        		'$var17',
	        		'$var18',
	        		'$var19'
	        	)
	        ";
	         $iAffectedRows = $dbh->exec($sSql);
    		 echo "Affected rows: $iAffectedRows <hr/>";
	    }
	    if (!feof($handle)) {
	        echo "Error: unexpected fgets() fail\n";
	    }
	    fclose($handle);
	}

?>
